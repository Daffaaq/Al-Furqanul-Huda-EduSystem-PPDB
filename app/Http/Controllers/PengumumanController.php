<?php

namespace App\Http\Controllers;

use App\Models\BiodataCalonSiswa;
use App\Models\JadwalPendaftaran;
use App\Models\NilaiAkademikPendaftar;
use App\Models\Pendaftaran;
use App\Models\PrestasiPendaftar;
use Illuminate\Http\Request;
use App\Traits\PendaftaranFilterTrait;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class PengumumanController extends Controller
{
    use PendaftaranFilterTrait;
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:pengumuman.index')->only('index', 'list');
        $this->middleware('permission:pengumuman.create')->only('create', 'store');
        $this->middleware('permission:pengumuman.edit')->only('edit', 'update');
        $this->middleware('permission:pengumuman.destroy')->only('destroy');
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            $pendaftarans = DB::table('pendaftarans')
                ->leftJoin('biodata_calon_siswas', 'pendaftarans.biodata_calon_siswa_id', '=', 'biodata_calon_siswas.id')
                ->leftJoin('periodes', 'biodata_calon_siswas.periode_id', '=', 'periodes.id')
                ->leftJoin('jadwal_pendaftarans', 'pendaftarans.jadwal_pendaftaran_id', '=', 'jadwal_pendaftarans.id')
                ->where('is_final', true)
                ->select(
                    'pendaftarans.id',
                    'biodata_calon_siswas.nama_calon_siswa as nama_calon_siswa',
                    'biodata_calon_siswas.user_id as biodata_user_id',
                    'pendaftarans.nomer_pendaftaran',
                    'pendaftarans.status_final',
                    'jadwal_pendaftarans.gelombang_pendaftaran',
                    'pendaftarans.status_accept',
                    'pendaftarans.is_final',
                    'pendaftarans.status_cadangan',
                    'pendaftarans.status_aktif',
                    'pendaftarans.status_diskualifikasi'
                );

            $pendaftarans = $this->applyPendaftaranFilters($request, $pendaftarans)
                ->get()
                ->transform(function ($pendaftaran) {
                    $nilaiAkademik = DB::table('nilai_akademik_pendaftars')
                        ->where('pendaftaran_id', $pendaftaran->id)
                        ->where('status', 'Accept')
                        ->avg('nilai') ?? 0;

                    $prestasiList = DB::table('prestasi_pendaftars')
                        ->where('pendaftaran_id', $pendaftaran->id)
                        ->get()
                        ->map(function ($prestasi) {
                            return [
                                'jumlah' => $prestasi->jumlah_prestasi,
                                'poin_per_prestasi' => $prestasi->point_prestasi ?? 0,
                            ];
                        })
                        ->toArray();

                    $nilaiAkhir = \App\Helpers\PrestasiHelper::hitungNilaiAkhir($nilaiAkademik, $prestasiList);
                    $pendaftaran->total_nilai = round($nilaiAkhir, 2);
                    return $pendaftaran;
                })
                ->sortByDesc('total_nilai') // ✅ Tambahkan ini untuk urutkan dari nilai tertinggi
                ->values()
                ->map(function ($pendaftaran, $index) {
                    $pendaftaran->peringkat = $index + 1; // 🔥 Tambahkan peringkat manual
                    // ✅ Tambahkan ini:
                    $authUserId = auth()->id();
                    $pendaftaran->is_current_user = $pendaftaran->biodata_user_id == $authUserId;
                    return $pendaftaran;
                }); // reset index agar urutan tetap rapih

            return DataTables::of($pendaftarans)
                ->addIndexColumn()
                ->addColumn('total_nilai', function ($row) {
                    return $row->total_nilai;
                })
                ->make(true);
        }
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pengumuman.index');
    }


    public function reRegistration()
    {
        // Ambil periode aktif
        $periode = DB::table('periodes')->where('status_periode', 1)->first();

        if (!$periode) {
            return response()->json(['message' => 'Periode belum aktif'], 400);
        }

        // Ambil biodata calon siswa berdasarkan user yang login & periode aktif
        $biodata = BiodataCalonSiswa::where('user_id', auth()->id())
            ->where('periode_id', $periode->id)
            ->first();

        if (!$biodata) {
            return response()->json(['message' => 'Biodata calon siswa tidak ditemukan'], 400);
        }

        // Ambil pendaftaran terakhir (paling baru) yang memenuhi semua kriteria
        $pendaftaran = Pendaftaran::where('biodata_calon_siswa_id', $biodata->id)
            ->where('status_final', 'Lolos')
            ->where('status_diskualifikasi', false)
            ->where('status_cadangan', false)
            ->orderByDesc('created_at') // atau 'id' kalau ID auto-increment
            ->first();

        if (!$pendaftaran) {
            return response()->json(['message' => 'Tidak ditemukan pendaftaran yang valid'], 400);
        }

        // Cek apakah sudah pernah daftar ulang (accept atau reject)
        if (in_array($pendaftaran->status_accept, ['Accept', 'Reject'])) {
            return response()->json(['message' => 'Tidak dapat daftar ulang karena sudah pernah melakukan tindakan sebelumnya'], 400);
        }

        // Lanjutkan daftar ulang
        $pendaftaran->update([
            'status_accept' => 'Accept'
        ]);

        return response()->json(['message' => 'Berhasil melakukan daftar ulang'], 200);
    }


    public function handleCadanganDecision(Request $request)
    {
        $request->validate([
            'decision' => 'required|in:accept,reject',
        ]);

        $periode = DB::table('periodes')->where('status_periode', 1)->first();

        if (!$periode) {
            return response()->json(['message' => 'Periode belum aktif'], 400);
        }

        $biodata = BiodataCalonSiswa::where('user_id', auth()->id())
            ->where('periode_id', $periode->id)
            ->first();

        if (!$biodata) {
            return response()->json(['message' => 'Biodata tidak ditemukan'], 400);
        }

        $pendaftaran = Pendaftaran::where('biodata_calon_siswa_id', $biodata->id)
            ->where('status_cadangan', true)
            ->orderByDesc('created_at')
            ->first();

        if (!$pendaftaran) {
            return response()->json(['message' => 'Data pendaftaran cadangan tidak ditemukan'], 400);
        }

        if (!$pendaftaran->status_cadangan) {
            return response()->json(['message' => 'Keputusan sudah diproses sebelumnya.'], 400);
        }

        if ($request->decision === 'accept') {
            $jadwalSelanjutnya = JadwalPendaftaran::where('id', '>', $pendaftaran->jadwal_pendaftaran_id)
                ->orderBy('id', 'asc')
                ->first();

            if (!$jadwalSelanjutnya) {
                return response()->json([
                    'message' => 'Mohon maaf, belum tersedia jadwal tahap berikutnya. Silakan hubungi panitia atau coba lagi nanti.',
                ], 400);
            }

            $jadwalSelanjutnya->update([
                'kuota_akun' => $jadwalSelanjutnya->kuota_akun - 1,
                'kuota_pendaftaran' => $jadwalSelanjutnya->kuota_pendaftaran - 1,
            ]);


            $pendaftaranBaru = Pendaftaran::create([
                'biodata_calon_siswa_id' => $biodata->id,
                'jadwal_pendaftaran_id' => $jadwalSelanjutnya->id,
                'status_final' => 'Pending',
                'status_aktif' => true,
                'tanggal_pendaftaran' => now(),
            ]);

            // Clone nilai akademik
            $nilaiList = NilaiAkademikPendaftar::where('pendaftaran_id', $pendaftaran->id)->get();
            foreach ($nilaiList as $nilai) {
                NilaiAkademikPendaftar::create([
                    'pendaftaran_id' => $pendaftaranBaru->id,
                    'mata_pelajaran_seleksi_id' => $nilai->mata_pelajaran_seleksi_id,
                    'nilai' => $nilai->nilai,
                    'status' => 'Pending',
                ]);
            }

            // Clone prestasi
            $prestasiList = PrestasiPendaftar::where('pendaftaran_id', $pendaftaran->id)->get();
            foreach ($prestasiList as $prestasi) {
                PrestasiPendaftar::create([
                    'pendaftaran_id' => $pendaftaranBaru->id,
                    'prestasi_id' => $prestasi->prestasi_id,
                    'jumlah_prestasi' => $prestasi->jumlah_prestasi,
                    'dokumen_prestasi_pendukung' => $prestasi->dokumen_prestasi_pendukung,
                    'file_type' => $prestasi->file_type,
                    'status' => 'Pending',
                ]);
            }

            // Tandai cadangan lama sudah diproses
            $pendaftaran->update(['status_cadangan' => false]);

            return response()->json([
                'message' => 'Selamat! Anda telah memilih untuk melanjutkan ke tahap berikutnya. Data Anda telah diproses ulang.',
                'status' => 'lanjut'
            ]);
        }

        // Jika reject → update status_cadangan saja
        $pendaftaran->update(['status_cadangan' => false]);

        return response()->json([
            'message' => 'Terima kasih telah mengikuti proses pendaftaran. Semoga sukses di kesempatan berikutnya.',
            'status' => 'tidak_lanjut'
        ]);
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
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
