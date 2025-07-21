<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBiodataCalonSiswaRequest;
use App\Http\Requests\UpdateBiodataCalonSiswaRequest;
use App\Models\BiodataCalonSiswa;
use App\Models\Pendaftaran;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Traits\FilterByPeriode;
use Illuminate\Support\Facades\DB;

class BiodataCalonSiswaController extends Controller
{
    use FilterByPeriode;
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:biodata-calon-siswa.index')->only('index', 'list');
        $this->middleware('permission:biodata-calon-siswa.create')->only('create', 'store');
        $this->middleware('permission:biodata-calon-siswa.edit')->only('edit', 'update');
        $this->middleware('permission:biodata-calon-siswa.destroy')->only('destroy');
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            $biodataCalonSiswa = DB::table('biodata_calon_siswas')
                ->leftJoin('periodes', 'biodata_calon_siswas.periode_id', '=', 'periodes.id')
                ->leftJoin('users', 'biodata_calon_siswas.user_id', '=', 'users.id')
                ->select('users.name', 'users.email', 'biodata_calon_siswas.id', 'periodes.nama_periode');

            //filter periode
            $biodataCalonSiswa = $this->applyPeriodeFilter($request, $biodataCalonSiswa);

            return DataTables::of($biodataCalonSiswa)
                ->addIndexColumn()
                ->make(true);
        }
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
            $pendaftaran = \App\Models\Pendaftaran::where('biodata_calon_siswa_id', $biodataCalonSiswa->id)
                ->where('status_aktif', 1)
                ->first();

            $jadwal = $pendaftaran?->jadwalPendaftaran;

            // Cek apakah biodata ditutup atau sudah melewati deadline
            $biodataBolehDiupdate = true;
            if (!$jadwal || $jadwal->biodata_ditutup == 1 || now()->gt($jadwal->deadline_biodata_calon_siswa)) {
                $biodataBolehDiupdate = false;
            }

            
            return view('biodata-calon-siswa.calon-siswa.index', compact('biodataCalonSiswa', 'biodataBolehDiupdate', 'pendaftaran'));
        } else {
            return view('biodata-calon-siswa.manajemen.index');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $periode = DB::table('periodes')->where('status_periode', 1)->first();
        $now = now();

        // Ambil jadwal yang sedang aktif
        $jadwalPendaftaranAktif = DB::table('jadwal_pendaftarans')
            ->where('periode_id', $periode->id)
            ->where('tanggal_mulai_jadwal_pendaftaran', '<=', $now)
            ->where('tanggal_selesai_jadwal_pendaftaran', '>=', $now->format('Y-m-d'))
            ->first();

        // Pastikan jadwal pendaftaran aktif ada
        if (!$jadwalPendaftaranAktif) {
            return redirect()->route('biodata-calon-siswa.index')->with('error', 'Jadwal pendaftaran tidak ditemukan');
        }

        // Hitung jumlah pendaftar untuk jadwal aktif ini
        $jumlahPendaftar = DB::table('pendaftarans')
            ->where('jadwal_pendaftaran_id', $jadwalPendaftaranAktif->id)
            ->distinct('biodata_calon_siswa_id') // jaga-jaga 1 siswa daftar dua kali
            ->count(DB::raw('DISTINCT biodata_calon_siswa_id'));

        // Cek apakah kuota sudah penuh
        if ($jumlahPendaftar >= $jadwalPendaftaranAktif->kuota_akun) {
            return redirect()->route('biodata-calon-siswa.index')->with('error', 'Jadwal pendaftaran sudah penuh');
        }

        // Kirim data ke view
        return view('biodata-calon-siswa.manajemen.create', compact('periode', 'jadwalPendaftaranAktif', 'jumlahPendaftar'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBiodataCalonSiswaRequest $request)
    {
        // Ambil periode yang aktif
        $periode = DB::table('periodes')->where('status_periode', 1)->first();

        if (!$periode) {
            return redirect()->back()->with('error', 'Periode pendaftaran belum dibuka');
        }

        $jadwalPendaftaran = DB::table('jadwal_pendaftarans')->where('periode_id', $periode->id)->first();

        if (!$jadwalPendaftaran) {
            return redirect()->back()->with('error', 'Periode pendaftaran belum dibuka');
        }

        $now = now();
        // Ambil jadwal yang sedang aktif
        $jadwalPendaftaranAktif = DB::table('jadwal_pendaftarans')
            ->where('periode_id', $periode->id)
            ->where('tanggal_mulai_jadwal_pendaftaran', '<=', $now)
            ->where('tanggal_selesai_jadwal_pendaftaran', '>=', $now->format('Y-m-d'))
            ->first();

        if (!$jadwalPendaftaranAktif) {
            return redirect()->back()->with('error', 'pendaftaran belum dibuka');
        }

        // Mulai transaksi untuk memastikan data konsisten
        DB::beginTransaction();

        try {
            // Membuat User baru
            $User = User::create([
                'name' => $request->validated()['name'],
                'email' => $request->validated()['email'],
                'password' => bcrypt($request->validated()['password']),
                'email_verified_at' => now()
            ]);

            // Menyimpan foto formal jika ada
            $fotoFormalPath = null;
            if ($request->hasFile('foto_formal_calon_siswa')) {
                $fotoFormal = $request->file('foto_formal_calon_siswa');
                $fotoFormalPath = $fotoFormal->store('foto_formal', 'public');
            }

            // Membuat Biodata Calon Siswa baru
            $BiodataCalonSiswa = BiodataCalonSiswa::create([
                'user_id' => $User->id,
                'nama_calon_siswa' => $request->validated()['nama_calon_siswa'],
                'jenis_kelamin_calon_siswa' => $request->validated()['jenis_kelamin_calon_siswa'],
                'email_calon_siswa' => $request->validated()['email'],
                'alamat_calon_siswa' => $request->validated()['alamat_calon_siswa'],
                'tanggal_lahir_calon_siswa' => $request->validated()['tanggal_lahir_calon_siswa'],
                'tempat_lahir_calon_siswa' => $request->validated()['tempat_lahir_calon_siswa'],
                'nomor_telepon_calon_siswa' => $request->validated()['nomor_telepon_calon_siswa'],
                'foto_formal_calon_siswa' => $fotoFormalPath,
                'periode_id' => $periode->id
            ]);

            $pendaftaran = Pendaftaran::create([
                'jadwal_pendaftaran_id' => $jadwalPendaftaranAktif->id,
                'biodata_calon_siswa_id' => $BiodataCalonSiswa->id
            ]);

            // Assign role siswa ke user yang baru
            $User->assignRole('calon-siswa');

            // Commit transaksi
            DB::commit();

            return redirect()->route('landing-page')->with('success', 'Pendaftaran Berhasil');
        } catch (\Exception $e) {
            // Jika terjadi kesalahan, rollback transaksi
            DB::rollback();

            // Tampilkan error
            return redirect()->back()->with('error', 'Terjadi kesalahan saat pendaftaran');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(BiodataCalonSiswa $biodataCalonSiswa)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BiodataCalonSiswa $biodataCalonSiswa)
    {
        return view('biodata-calon-siswa.edit', compact('biodataCalonSiswa'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBiodataCalonSiswaRequest $request, BiodataCalonSiswa $biodataCalonSiswa)
    {
        $biodataCalonSiswa->update($request->validated());
        return redirect()->route('biodata-calon-siswa.index')->with('success', 'Data berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BiodataCalonSiswa $biodataCalonSiswa)
    {
        //
    }
}
