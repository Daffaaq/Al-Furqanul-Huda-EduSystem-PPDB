<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSeleksiRequest;
use App\Models\BiodataCalonSiswa;
use App\Models\DiskualifikasiPendaftar;
use App\Models\Pendaftaran;
use App\Models\Prestasi;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Traits\FilterByPeriode;
use App\Traits\PendaftaranFilterTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\NilaiAkademikPendaftar;
use App\Models\NilaiAkademikPendaftarStatusHistories;
use App\Models\PrestasiPendaftar;
use App\Models\PrestasiPendaftarStatusHistories;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PendaftaranController extends Controller
{
    use FilterByPeriode, PendaftaranFilterTrait;
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:pendaftaran.index')->only('index', 'list');
        $this->middleware('permission:pendaftaran.create')->only('create', 'store');
        $this->middleware('permission:pendaftaran.edit')->only('edit', 'update');
        $this->middleware('permission:pendaftaran.destroy')->only('destroy');
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            $pendaftaran = DB::table('pendaftarans')
                ->leftJoin('biodata_calon_siswas', 'pendaftarans.biodata_calon_siswa_id', '=', 'biodata_calon_siswas.id')
                ->leftJoin('periodes', 'biodata_calon_siswas.periode_id', '=', 'periodes.id')
                ->leftJoin('jadwal_pendaftarans', 'pendaftarans.jadwal_pendaftaran_id', '=', 'jadwal_pendaftarans.id')
                ->select(
                    'pendaftarans.id',
                    'biodata_calon_siswas.nama_calon_siswa as nama_calon_siswa',
                    'pendaftarans.tanggal_pendaftaran',
                    'pendaftarans.status_final',
                    'periodes.nama_periode as nama_periode',
                    'jadwal_pendaftarans.gelombang_pendaftaran',
                    'pendaftarans.status_accept',
                    'pendaftarans.is_final',
                    'pendaftarans.status_cadangan',
                    'pendaftarans.status_aktif',
                    'pendaftarans.status_diskualifikasi'
                );

            // ✅ Panggil trait filter
            $pendaftaran = $this->applyPendaftaranFilters($request, $pendaftaran);

            return DataTables::of($pendaftaran)
                ->addIndexColumn()
                ->make(true);
        }
    }

    public function getGelombang(Request $request)
    {
        $gelombangs = DB::table('jadwal_pendaftarans')
            ->where('periode_id', $request->periode_id)
            ->distinct()
            ->pluck('gelombang_pendaftaran');

        return response()->json($gelombangs);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $periode = DB::table('periodes')->where('status_periode', 1)->first();
        if (!$periode) {
            return redirect()->route('dashboard')->with('error', 'Periode pendaftaran belum dibuka');
        }
        //auth
        $user = auth()->user();
        if ($user->hasRole('calon-siswa')) {
            $biodataCalonSiswa = BiodataCalonSiswa::where('user_id', $user->id)->where('periode_id', $periode->id)->first();
            if (!$biodataCalonSiswa) {
                return redirect()->route('dashboard')->with('error', 'Biodata Calon Siswa pada periode' . $periode->nama_periode . ' belum dibuat');
            }
            $pendaftaran = Pendaftaran::where('biodata_calon_siswa_id', $biodataCalonSiswa->id)->get();
            // dd($pendaftaran);
            if (!$pendaftaran) {
                return redirect()->route('dashboard')->with('error', 'Pendaftaran Calon Siswa pada periode' . $periode->nama_periode . ' belum dibuat');
            }
            $pendaftaranTerakhir = $pendaftaran->where('status_aktif', 1)
                ->first();
            if (!$pendaftaranTerakhir) {
                return redirect()->route('dashboard')->with('error', 'Pendaftaran Calon Siswa belum ditemukan.');
            }
            $totalPrestasi = $pendaftaranTerakhir->prestasiPendaftars->sum('jumlah_prestasi');

            $jadwal = $pendaftaranTerakhir?->jadwalPendaftaran;

            // cek apakah pendaftaran ditutup
            $nilaibolehdiupdate = true;
            if (!$jadwal || $jadwal->upload_ditutup == 1 || now()->gt($jadwal->deadline_upload_pendaftaran)) {
                $nilaibolehdiupdate = false;
            }
            return view('pendaftaran.calon-siswa.index', compact('pendaftaran', 'biodataCalonSiswa', 'totalPrestasi', 'pendaftaranTerakhir', 'nilaibolehdiupdate'));
        } else {
            return view('pendaftaran.manajemen.index');
        }
    }

    public function daftar(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $pendaftaran = Pendaftaran::findOrFail($id);

            $nilaiAkademik = DB::table('nilai_akademik_pendaftars')
                ->where('pendaftaran_id', $pendaftaran->id)
                ->pluck('status');

            // Cek apakah masih ada yang belum di-ACC
            if ($nilaiAkademik->contains(function ($status) {
                return $status !== 'Accept';
            })) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Semua nilai akademik harus disetujui (Accept) sebelum mendaftar.',
                ]);
            }

            if ($pendaftaran->nomer_pendaftaran !== null && $pendaftaran->tanggal_pendaftaran !== null && $pendaftaran->is_final == true) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Anda Sudah Melakukan Pendaftaran',
                ]);
            }

            $tahun = Carbon::now()->format('Y');
            $prefix = 'AFH-' . $tahun . '-';

            $maxRetry = 5;
            $attempt = 0;
            $nomorPendaftaran = null;

            do {
                // Ambil nomor terakhir yang sudah dipakai tahun ini
                $last = Pendaftaran::whereYear('created_at', $tahun)
                    ->whereNotNull('nomer_pendaftaran')
                    ->lockForUpdate()
                    ->orderByDesc('nomer_pendaftaran')
                    ->first();

                $lastNumber = 0;
                if ($last) {
                    $parts = explode('-', $last->nomer_pendaftaran);
                    $lastNumber = (int) end($parts);
                }

                $newNumber = $lastNumber + 1 + $attempt;
                $noUrut = str_pad($newNumber, 5, '0', STR_PAD_LEFT);
                $nomorPendaftaran = $prefix . $noUrut;

                $exists = Pendaftaran::where('nomer_pendaftaran', $nomorPendaftaran)->exists();

                if ($exists) {
                    $attempt++;
                } else {
                    break;
                }
            } while ($attempt < $maxRetry);

            if ($exists) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal membuat nomor pendaftaran yang unik. Silakan coba lagi.',
                ]);
            }

            $pendaftaran->update([
                'is_final' => true,
                'nomer_pendaftaran' => $nomorPendaftaran,
                'tanggal_pendaftaran' => now(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pendaftaran Berhasil',
                'nomor_pendaftaran' => $nomorPendaftaran,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Pendaftaran Gagal',
                'error' => $e->getMessage(),
            ]);
        }
    }


    public function showRaport($id)
    {
        $periode = DB::table('periodes')->where('status_periode', 1)->first();
        if (!$periode) {
            return redirect()->route('dashboard')->with('error', 'Periode pendaftaran belum dibuka');
        }
        $user = auth()->user();
        $biodataCalonSiswa = BiodataCalonSiswa::where('user_id', $user->id)->where('periode_id', $periode->id)->first();
        if (!$biodataCalonSiswa) {
            return redirect()->route('dashboard')->with('error', 'Biodata Calon Siswa pada periode ' . $periode->nama_periode . ' belum dibuat');
        }
        $pendaftaran = Pendaftaran::with('biodataCalonSiswa')->where('biodata_calon_siswa_id', $biodataCalonSiswa->id)->findOrFail($id);
        $mataPelajaranSeleksi = DB::table('mata_pelajaran_seleksis')
            ->where('periode_id', $periode->id)
            ->select('id', 'nama_mata_pelajaran_seleksi')
            ->get();

        $prestasi = Prestasi::with('kategori_prestasi')
            ->whereHas('kategori_prestasi', function ($query) use ($periode) {
                $query->where('periode_id', $periode->id);
            })
            ->get();

        $nilaiAkademik = NilaiAkademikPendaftar::where('pendaftaran_id', $pendaftaran->id)->pluck('nilai', 'mata_pelajaran_seleksi_id');
        $prestasiPendaftar = PrestasiPendaftar::where('pendaftaran_id', $pendaftaran->id)
            ->get()
            ->keyBy('prestasi_id');

        return view('pendaftaran.calon-siswa.seleksi', compact(
            'pendaftaran',
            'mataPelajaranSeleksi',
            'prestasi',
            'nilaiAkademik',
            'prestasiPendaftar'
        ));
    }

    public function storeSeleksi(StoreSeleksiRequest $request, $id)
    {
        $pendaftaran = Pendaftaran::findOrFail($id);

        // Simpan dokumen nilai (raport/SKL)
        if ($request->hasFile('dokumen_nilai_pendukung')) {
            $file = $request->file('dokumen_nilai_pendukung');
            $filename = 'nilai_' . time() . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('dokumen/nilai', $filename, 'public');

            // Simpan ke kolom dokumen_pendukung di tabel biodata_calon_siswas
            $biodata = $pendaftaran->biodataCalonSiswa;
            if ($biodata) {
                $biodata->dokumen_pendukung = $filePath;
                $biodata->save();
            }
        }



        // Simpan Nilai Akademik
        foreach ($request->nilai_akademik as $mapelId => $nilai) {
            if ($nilai !== null) {
                $existing = \App\Models\NilaiAkademikPendaftar::where('pendaftaran_id', $pendaftaran->id)
                    ->where('mata_pelajaran_seleksi_id', $mapelId)
                    ->first();

                if ($existing) {
                    // Hanya update jika statusnya Pending
                    if ($existing->status === 'Pending') {
                        $existing->update([
                            'nilai' => $nilai
                            // Tidak perlu mengubah status
                        ]);
                    }

                    // Jika status bukan Pending, skip (tidak diubah)
                } else {
                    // Jika belum ada, insert baru dengan status Pending
                    \App\Models\NilaiAkademikPendaftar::create([
                        'pendaftaran_id' => $pendaftaran->id,
                        'mata_pelajaran_seleksi_id' => $mapelId,
                        'nilai' => $nilai,
                        'status' => 'Pending',
                    ]);
                }
            }
        }

        $periode = DB::table('periodes')->where('status_periode', 1)->first();

        $semuaPrestasi = Prestasi::with('kategori_prestasi')
            ->whereHas('kategori_prestasi', function ($query) use ($periode) {
                $query->where('periode_id', $periode->id);
            })
            ->get();

        // Ambil semua prestasi_id dari input
        $inputPrestasiIds = [];

        $jumlahTotalPrestasi = collect($request->input('jumlah_prestasi', []))
            ->map(fn($jumlah) => (int)$jumlah)
            ->sum();

        if ($jumlahTotalPrestasi > 3) {
            return redirect()->back()->withInput()->withErrors([
                'jumlah_prestasi' => 'Maksimal hanya 3 prestasi yang boleh diinput.',
            ]);
        }


        foreach ($semuaPrestasi as $p) {
            $prestasiId = $p->id;
            $jumlah = $request->input("jumlah_prestasi.$prestasiId");

            if ($jumlah && $jumlah > 0) {
                $inputPrestasiIds[] = $prestasiId;

                // Cek jika prestasi sudah pernah disimpan
                $existing = PrestasiPendaftar::where('pendaftaran_id', $pendaftaran->id)
                    ->where('prestasi_id', $prestasiId)
                    ->first();

                // Jika sudah ada dan status bukan Pending, skip update
                if ($existing && $existing->status !== 'Pending') {
                    continue; // lewati
                }

                $dokumenPath = null;
                $fileType = null;

                if ($request->hasFile("dokumen_prestasi_pendukung.$prestasiId")) {
                    $file = $request->file("dokumen_prestasi_pendukung.$prestasiId");
                    $fileType = $file->getClientOriginalExtension();
                    $filename = "prestasi_{$prestasiId}_" . time() . '.' . $fileType;
                    $dokumenPath = $file->storeAs("dokumen/prestasi", $filename, 'public');
                }

                $dataToUpdate = [
                    'jumlah_prestasi' => $jumlah,
                ];

                if ($dokumenPath) {
                    $dataToUpdate['dokumen_prestasi_pendukung'] = $dokumenPath;
                    $dataToUpdate['file_type'] = $fileType;
                }

                PrestasiPendaftar::updateOrCreate(
                    [
                        'pendaftaran_id' => $pendaftaran->id,
                        'prestasi_id' => $prestasiId,
                    ],
                    array_merge($dataToUpdate, [
                        'status' => 'Pending',
                    ])
                );
            }
        }

        // Hapus prestasi yang tidak dikirim ulang
        PrestasiPendaftar::where('pendaftaran_id', $pendaftaran->id)
            ->whereNotIn('prestasi_id', $inputPrestasiIds)
            ->where('status', 'Pending')
            ->each(function ($prestasi) {
                // Optional: Hapus file lama dari storage
                if ($prestasi->dokumen_prestasi_pendukung && Storage::disk('public')->exists($prestasi->dokumen_prestasi_pendukung)) {
                    Storage::disk('public')->delete($prestasi->dokumen_prestasi_pendukung);
                }

                // Hapus data
                $prestasi->delete();
            });


        return redirect()->route('pendaftaran.index')->with('success', 'Nilai dan prestasi berhasil disimpan.');
    }

    // Di PendaftaranController.php
    public function updateStatusSemua(Request $request)
    {
        $request->validate([
            'status' => 'required|in:Accept,Reject',
            'periode_id' => 'nullable|exists:periodes,id',
            'gelombang' => 'nullable|string'
        ]);

        $statusBaru = $request->status;
        $periodeId = $request->periode_id;
        $gelombang = $request->gelombang;
        $namaPetugas = Auth::user()->name ?? 'Admin';
        $catatan = '-';

        // Ambil semua pendaftaran sesuai filter
        $query = DB::table('pendaftarans')
            ->join('biodata_calon_siswas', 'pendaftarans.biodata_calon_siswa_id', '=', 'biodata_calon_siswas.id')
            ->join('jadwal_pendaftarans', 'pendaftarans.jadwal_pendaftaran_id', '=', 'jadwal_pendaftarans.id');

        if ($periodeId) {
            $query->where('biodata_calon_siswas.periode_id', $periodeId);
        }

        if ($gelombang) {
            $query->where('jadwal_pendaftarans.gelombang_pendaftaran', $gelombang);
        }

        $pendaftarans = $query->select('pendaftarans.id')->get();

        foreach ($pendaftarans as $item) {
            $nilaiItems = NilaiAkademikPendaftar::where('pendaftaran_id', $item->id)->get();
            foreach ($nilaiItems as $nilai) {
                if ($nilai->status !== $statusBaru) {
                    NilaiAkademikPendaftarStatusHistories::create([
                        'nilai_akademik_pendaftar_id' => $nilai->id,
                        'status' => $statusBaru,
                        'catatan' => $catatan,
                        'nama_petugas' => $namaPetugas,
                    ]);
                    $nilai->update(['status' => $statusBaru]);
                }
            }

            $prestasiItems = PrestasiPendaftar::where('pendaftaran_id', $item->id)->get();
            foreach ($prestasiItems as $prestasi) {
                if ($prestasi->status !== $statusBaru) {
                    PrestasiPendaftarStatusHistories::create([
                        'prestasi_pendaftar_id' => $prestasi->id,
                        'status' => $statusBaru,
                        'catatan' => $catatan,
                        'nama_petugas' => $namaPetugas,
                    ]);
                    $prestasi->update(['status' => $statusBaru]);
                }
            }
        }

        return response()->json([
            'status' => true,
            'message' => "Status semua pendaftaran berhasil diubah ke '$statusBaru'."
        ]);
    }


    public function updateStatusSeleksi(Request $request, $pendaftaranId)
    {
        $request->validate([
            'nilai' => 'nullable|array',
            'nilai.*.id' => 'required|integer|exists:nilai_akademik_pendaftars,id',
            'nilai.*.status' => 'required|in:Accept,Reject,Pending',
            'nilai.*.catatan' => 'nullable|string',

            'prestasi' => 'nullable|array',
            'prestasi.*.id' => 'nullable|integer|exists:prestasi_pendaftars,id',
            'prestasi.*.status' => 'nullable|in:Accept,Reject,Pending',
            'prestasi.*.catatan' => 'nullable|string',
        ]);

        $namaPetugas = Auth::user()->name ?? 'Admin';

        // Update status nilai satu per satu
        foreach ($request->nilai ?? [] as $item) {
            $nilai = NilaiAkademikPendaftar::find($item['id']);
            if ($nilai && $nilai->status !== $item['status']) {
                NilaiAkademikPendaftarStatusHistories::create([
                    'nilai_akademik_pendaftar_id' => $nilai->id,
                    'status' => $item['status'],
                    'catatan' => $item['catatan'] ?? null,
                    'nama_petugas' => $namaPetugas,
                ]);

                $nilai->update(['status' => $item['status']]);
            }
        }

        // Update status prestasi satu per satu
        foreach ($request->prestasi ?? [] as $item) {
            $prestasi = PrestasiPendaftar::find($item['id']);
            if ($prestasi && $prestasi->status !== $item['status']) {
                PrestasiPendaftarStatusHistories::create([
                    'prestasi_pendaftar_id' => $prestasi->id,
                    'status' => $item['status'],
                    'catatan' => $item['catatan'] ?? null,
                    'nama_petugas' => $namaPetugas,
                ]);

                $prestasi->update(['status' => $item['status']]);
            }
        }

        return response()->json([
            'status' => true,
            'message' => 'Status nilai dan prestasi berhasil diperbarui.'
        ]);
    }

    public function updateStatusSeleksiAkademik(Request $request, $id)
    {
        $nilaiAkademikPendaftar = NilaiAkademikPendaftar::find($id);

        if (!$nilaiAkademikPendaftar) {
            return response()->json([
                'status' => false,
                'message' => 'Nilai akademik tidak ditemukan.'
            ]);
        };
        //validation
        $request->validate([
            'status' => 'required|in:Accept,Reject',
            'catatan' => 'nullable|string',
        ]);
        $nilaiAkademikPendaftar->update($request->all());

        NilaiAkademikPendaftarStatusHistories::create([
            'nilai_akademik_pendaftar_id' => $id,
            'status' => $request->status,
            'catatan' => $request->catatan,
            'nama_petugas' => Auth::user()->name,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Status nilai akademik berhasil diperbarui.'
        ]);
    }

    public function updateStatusSeleksiPrestasi(Request $request, $id)
    {
        $prestasiPendaftar = PrestasiPendaftar::find($id);

        if (!$prestasiPendaftar) {
            return response()->json([
                'status' => false,
                'message' => 'Prestasi tidak ditemukan.'
            ]);
        };
        //validation
        $request->validate([
            'status' => 'required|in:Accept,Reject',
            'catatan' => 'nullable|string',
        ]);
        $prestasiPendaftar->update($request->all());

        PrestasiPendaftarStatusHistories::create([
            'prestasi_pendaftar_id' => $id,
            'status' => $request->status,
            'catatan' => $request->catatan,
            'nama_petugas' => Auth::user()->name,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Status prestasi berhasil diperbarui.'
        ]);
    }

    public function showStatusHistory($pendaftaranId)
    {
        $pendaftaran = Pendaftaran::with('biodataCalonSiswa')->findOrFail($pendaftaranId);

        // Ambil semua nilai akademik & prestasi berdasarkan pendaftaran
        $nilaiIds = NilaiAkademikPendaftar::where('pendaftaran_id', $pendaftaranId)->pluck('id');
        $prestasiIds = PrestasiPendaftar::where('pendaftaran_id', $pendaftaranId)->pluck('id');

        // Ambil history status nilai & prestasi
        $nilaiHistories = NilaiAkademikPendaftarStatusHistories::whereIn('nilai_akademik_pendaftar_id', $nilaiIds)
            ->with('nilai_akademik_pendaftar.mataPelajaranSeleksi') // relasi opsional jika ada
            ->orderBy('created_at', 'desc')
            ->get();

        $prestasiHistories = PrestasiPendaftarStatusHistories::whereIn('prestasi_pendaftar_id', $prestasiIds)
            ->with('prestasi_pendaftar.prestasi.kategori_prestasi') // relasi opsional jika ada
            ->orderBy('created_at', 'desc')
            ->get();

        // dd($nilaiHistories, $prestasiHistories);

        return view('pendaftaran.manajemen.history-status', compact(
            'pendaftaran',
            'nilaiHistories',
            'prestasiHistories'
        ));
    }

    public function revisiNilaiAkademik(Request $request, $id)
    {
        $nilai = NilaiAkademikPendaftar::with('statusHistories')->findOrFail($id);

        return response()->json([
            'nilai' => $nilai->nilai,
            'status_histories' => $nilai->statusHistories->map(function ($item) {
                return [
                    'status' => $item->status,
                    'catatan' => $item->catatan,
                    'nama_petugas' => $item->nama_petugas,
                    'created_at' => $item->created_at->format('d M Y H:i')
                ];
            })
        ]);
    }

    public function updateNilaiAkademik(Request $request, $id)
    {
        $nilaiAkademikPendaftar = NilaiAkademikPendaftar::find($id);

        if (!$nilaiAkademikPendaftar) {
            return response()->json([
                'status' => false,
                'message' => 'Nilai akademik tidak ditemukan.'
            ]);
        };
        //validation
        $request->validate([
            'nilai' => 'required|numeric',
        ]);
        // Update nilai dan set status menjadi 'Pending'
        $nilaiAkademikPendaftar->update([
            'nilai' => $request->nilai,
            'status' => 'Pending',
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Nilai akademik berhasil diperbarui.'
        ]);
    }


    public function listSeleksiMapelAkademik($id, Request $request)
    {
        $latestHistory = DB::table('nikadtar_status_histories as h1')
            ->select('h1.nilai_akademik_pendaftar_id', 'h1.status as last_status', 'h1.catatan as last_catatan', 'h1.nama_petugas', 'h1.created_at')
            ->join(DB::raw('(
    SELECT nilai_akademik_pendaftar_id, MAX(created_at) AS max_created
    FROM nikadtar_status_histories
    GROUP BY nilai_akademik_pendaftar_id
  ) AS h2'), function ($join) {
                $join->on('h1.nilai_akademik_pendaftar_id', '=', 'h2.nilai_akademik_pendaftar_id')
                    ->on('h1.created_at', '=', 'h2.max_created');
            })
            ->whereRaw('h1.id = (
    SELECT MAX(id)
    FROM nikadtar_status_histories h3
    WHERE h3.nilai_akademik_pendaftar_id = h1.nilai_akademik_pendaftar_id
      AND h3.created_at = h2.max_created
  )');



        $query = DB::table('nilai_akademik_pendaftars')
            ->leftJoin('mata_pelajaran_seleksis', 'nilai_akademik_pendaftars.mata_pelajaran_seleksi_id', '=', 'mata_pelajaran_seleksis.id')
            ->leftJoinSub($latestHistory, 'last_hist', function ($join) {
                $join->on('nilai_akademik_pendaftars.id', '=', 'last_hist.nilai_akademik_pendaftar_id');
            })
            ->where('pendaftaran_id', $id)
            ->select(
                'nilai_akademik_pendaftars.id',
                'mata_pelajaran_seleksis.nama_mata_pelajaran_seleksi',
                'nilai_akademik_pendaftars.nilai',
                'nilai_akademik_pendaftars.status',
                DB::raw('COALESCE(last_hist.last_catatan, "") as catatan'),
                DB::raw('COALESCE(last_hist.last_status, "") as last_status'),
                DB::raw('COALESCE(last_hist.nama_petugas, "") as nama_petugas'),
                DB::raw('last_hist.created_at as last_updated_at')
            );

        // dd($query->get());

        return DataTables::of($query)
            ->addIndexColumn()
            ->make(true);
    }

    public function listSeleksiMapelAkademikAll($id, Request $request)
    {
        $query = DB::table('nilai_akademik_pendaftars')
            ->leftJoin('mata_pelajaran_seleksis', 'nilai_akademik_pendaftars.mata_pelajaran_seleksi_id', '=', 'mata_pelajaran_seleksis.id')
            ->leftJoin('pendaftarans', 'nilai_akademik_pendaftars.pendaftaran_id', '=', 'pendaftarans.id')
            ->leftJoin('biodata_calon_siswas', 'pendaftarans.biodata_calon_siswa_id', '=', 'biodata_calon_siswas.id')
            ->leftJoin('jadwal_pendaftarans', 'pendaftarans.jadwal_pendaftaran_id', '=', 'jadwal_pendaftarans.id')
            ->where('pendaftarans.biodata_calon_siswa_id', $id)
            ->select(
                'nilai_akademik_pendaftars.id',
                'mata_pelajaran_seleksis.nama_mata_pelajaran_seleksi',
                'nilai_akademik_pendaftars.nilai',
                'nilai_akademik_pendaftars.status',
                'jadwal_pendaftarans.gelombang_pendaftaran'
            );

        return DataTables::of($query)
            ->addIndexColumn()
            ->make(true);
    }
    public function listSeleksiPrestasi($id, Request $request)
    {
        $latestHistorySub = DB::table('prestar_status_histories as h1')
            ->select(
                'h1.prestasi_pendaftar_id',
                'h1.status as last_status',
                'h1.catatan as last_catatan',
                'h1.nama_petugas',
                'h1.created_at'
            )
            ->join(DB::raw('(
        SELECT prestasi_pendaftar_id, MAX(created_at) as max_created
        FROM prestar_status_histories
        GROUP BY prestasi_pendaftar_id
    ) as h2'), function ($join) {
                $join->on('h1.prestasi_pendaftar_id', '=', 'h2.prestasi_pendaftar_id')
                    ->on('h1.created_at', '=', 'h2.max_created');
            })
            // 🔒 Tambahan untuk cegah duplikat jika ada dua created_at yang sama
            ->whereRaw('h1.id = (
        SELECT MAX(id)
        FROM prestar_status_histories h3
        WHERE h3.prestasi_pendaftar_id = h1.prestasi_pendaftar_id
          AND h3.created_at = h1.created_at
    )');


        $query = DB::table('prestasi_pendaftars')
            ->leftJoin('prestasis', 'prestasi_pendaftars.prestasi_id', '=', 'prestasis.id')
            ->leftJoin('kategori_prestasis', 'prestasis.kategori_prestasi_id', '=', 'kategori_prestasis.id')
            ->leftJoinSub($latestHistorySub, 'last_hist', function ($join) {
                $join->on('prestasi_pendaftars.id', '=', 'last_hist.prestasi_pendaftar_id');
            })
            ->where('pendaftaran_id', $id)
            ->select(
                'prestasi_pendaftars.id',
                'kategori_prestasis.nama_kategori_prestasi',
                'prestasis.nama_prestasi',
                'prestasi_pendaftars.jumlah_prestasi',
                'prestasi_pendaftars.dokumen_prestasi_pendukung',
                'prestasi_pendaftars.status',
                DB::raw('COALESCE(last_hist.last_catatan, "") as catatan'),
                DB::raw('COALESCE(last_hist.last_status, "") as last_status'),
                DB::raw('COALESCE(last_hist.nama_petugas, "") as nama_petugas'),
                DB::raw('last_hist.created_at as last_updated_at')
            );

        return DataTables::of($query)
            ->addIndexColumn()
            ->make(true);
    }

    public function listSeleksiPrestasiAll($id, Request $request)
    {
        $query = DB::table('prestasi_pendaftars')
            ->leftJoin('prestasis', 'prestasi_pendaftars.prestasi_id', '=', 'prestasis.id')
            ->leftJoin('kategori_prestasis', 'prestasis.kategori_prestasi_id', '=', 'kategori_prestasis.id')
            ->leftJoin('pendaftarans', 'prestasi_pendaftars.pendaftaran_id', '=', 'pendaftarans.id')
            ->leftJoin('jadwal_pendaftarans', 'pendaftarans.jadwal_pendaftaran_id', '=', 'jadwal_pendaftarans.id')
            ->leftJoin('biodata_calon_siswas', 'pendaftarans.biodata_calon_siswa_id', '=', 'biodata_calon_siswas.id')
            ->where('pendaftarans.biodata_calon_siswa_id', $id)
            ->select(
                'prestasi_pendaftars.id',
                'kategori_prestasis.nama_kategori_prestasi',
                'prestasis.nama_prestasi',
                'prestasi_pendaftars.jumlah_prestasi',
                'prestasi_pendaftars.dokumen_prestasi_pendukung',
                'prestasi_pendaftars.status',
                'jadwal_pendaftarans.gelombang_pendaftaran'
            );

        return DataTables::of($query)
            ->addIndexColumn()
            ->make(true);
    }

    public function disqualify(Request $request, $id)
    {
        $request->validate([
            'alasan_diskualifikasi' => 'required|string',
            'bukti_diskualifikasi' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048', // max 2MB
        ]);

        DB::beginTransaction();

        try {
            $pendaftaran = Pendaftaran::findOrFail($id);
            if ($pendaftaran->status_diskualifikasi == true) {
                return response()->json(['error' => 'Pendaftaran ini sudah diskualifikasi.'], 400);
            }
            $pendaftaran->status_diskualifikasi = true;
            $pendaftaran->save();

            $diskualifikasiPendaftar = new DiskualifikasiPendaftar();
            $diskualifikasiPendaftar->pendaftaran_id = $pendaftaran->id;
            $diskualifikasiPendaftar->petugas_id = auth()->user()->id;
            $diskualifikasiPendaftar->alasan_diskualifikasi = $request->alasan_diskualifikasi;

            // Handle file upload jika ada
            if ($request->hasFile('bukti_diskualifikasi')) {
                $file = $request->file('bukti_diskualifikasi');
                $filename = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('diskualifikasi_bukti', $filename, 'public');
                // 'public' storage harus sudah di-link ke public/storage via `php artisan storage:link`

                $diskualifikasiPendaftar->bukti_diskualifikasi = $filePath;
                $diskualifikasiPendaftar->file_type = $file->getClientMimeType();
                $diskualifikasiPendaftar->tanggal_diskualifikasi = now();
            } else {
                $diskualifikasiPendaftar->tanggal_diskualifikasi = now();
            }

            $diskualifikasiPendaftar->save();

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Pendaftaran berhasil ditolak.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function cancelDisqualification($id)
    {
        DB::beginTransaction();

        try {
            $pendaftaran = Pendaftaran::findOrFail($id);

            if (!$pendaftaran->status_diskualifikasi) {
                return response()->json(['error' => 'Pendaftaran ini belum didiskualifikasi.'], 400);
            }

            // Set status_diskualifikasi = false
            $pendaftaran->status_diskualifikasi = false;
            $pendaftaran->save();

            // Hapus data diskualifikasi (jika hanya boleh satu)
            $diskualifikasi = DiskualifikasiPendaftar::where('pendaftaran_id', $pendaftaran->id)->first();
            if ($diskualifikasi) {
                // Hapus file jika ada
                if ($diskualifikasi->bukti_diskualifikasi && Storage::disk('public')->exists($diskualifikasi->bukti_diskualifikasi)) {
                    Storage::disk('public')->delete($diskualifikasi->bukti_diskualifikasi);
                }

                $diskualifikasi->delete();
            }

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Diskualifikasi berhasil dibatalkan.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }


    public function showDisqualify($id)
    {
        $pendaftaran = Pendaftaran::with('biodataCalonSiswa')->findOrFail($id);

        $diskualifikasiPendaftar = DiskualifikasiPendaftar::where('pendaftaran_id', $pendaftaran->id)->first();
        return view('pendaftaran.diskualifikasi.show', compact('diskualifikasiPendaftar'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = auth()->user();

        if (!$user->hasRole('calon-siswa')) {
            $pendaftaran = Pendaftaran::with(
                'jadwalPendaftaran',
                'biodataCalonSiswa.periode'
            )->find($id);

            return view('pendaftaran.show', compact('pendaftaran'));
        } else {
            return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pendaftaran $pendaftaran)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pendaftaran $pendaftaran)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pendaftaran $pendaftaran)
    {
        //
    }
}
