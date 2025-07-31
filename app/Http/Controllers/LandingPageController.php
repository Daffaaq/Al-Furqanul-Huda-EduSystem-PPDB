<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePendaftaranCalonSiswaRequest;
use App\Models\BiodataCalonSiswa;
use App\Models\Faq;
use App\Models\Pendaftaran;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LandingPageController extends Controller
{
    public function index()
    {
        // Ambil periode yang aktif
        $periode = DB::table('periodes')->where('status_periode', 1)->first();

        if (!$periode) {
            $jadwalPendaftaran = collect();
            $jadwalPendaftaranAktif = null;
        } else {
            $jadwalPendaftaran = DB::table('jadwal_pendaftarans')
                ->where('periode_id', $periode->id)
                ->get()
                ->map(function ($jadwal) {
                    $jumlahDaftar = DB::table('pendaftarans')
                        ->where('jadwal_pendaftaran_id', $jadwal->id)
                        ->distinct('biodata_calon_siswa_id') // jaga-jaga 1 siswa daftar dua kali
                        ->count('biodata_calon_siswa_id');

                    $jadwal->jumlah_daftar = $jumlahDaftar;
                    return $jadwal;
                });

            $now = now();

            // Ambil jadwal yang sedang aktif
            $jadwalPendaftaranAktif = DB::table('jadwal_pendaftarans')
                ->where('periode_id', $periode->id)
                ->where('status_jadwal_pendaftaran', 'Opened')
                ->first();
            if ($jadwalPendaftaranAktif) {
                // Hitung jumlah pendaftar untuk jadwal aktif ini
                $jumlahDaftar = DB::table('pendaftarans')
                    ->where('jadwal_pendaftaran_id', $jadwalPendaftaranAktif->id)
                    ->count();

                // Tambahkan property jumlah_daftar ke objek stdClass
                $jadwalPendaftaranAktif->jumlah_daftar = $jumlahDaftar;
            }
            $faqs = Faq::where('is_active', true)->where('periode_id', $periode->id)->orderBy('order')->select('question', 'answer')->get();
            $contact = DB::table('kontaks')->select('alamat', 'telepon', 'email', 'latitude', 'longitude', 'facebook', 'instagram', 'youtube', 'tiktok', 'whatsapp')->first();
            $jamOperasionals = DB::table('jam_operasionals')->select('id', 'hari', 'buka', 'tutup', 'tutup_full')->get();
        }

        return view('landing-page', compact('jadwalPendaftaran', 'jadwalPendaftaranAktif', 'faqs', 'contact', 'jamOperasionals'));
    }



    public function PendaftaranCalonSiswa(StorePendaftaranCalonSiswaRequest $request)
    {
        // Cek apakah pengguna sudah login (terautentikasi)
        if (Auth::check()) {
            return redirect()->route('dashboard')->with('error', 'Anda saat ini telah login dengan username ' . Auth::user()->name . ', silahkan logout terlebih dahulu.');
        }

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
                'name' => $request->validated()['nama_calon_siswa'],
                'email' => $request->validated()['email_calon_siswa'],
                'password' => bcrypt($request->validated()['password']),
                'email_verified_at' => now()
            ]);

            // Membuat Biodata Calon Siswa baru
            $BiodataCalonSiswa = BiodataCalonSiswa::create([
                'user_id' => $User->id,
                'jenis_kelamin_calon_siswa' => $request->validated()['jenis_kelamin'],
                'email_calon_siswa' => $request->validated()['email_calon_siswa'],
                'periode_id' => $periode->id
            ]);

            $pendaftaran = Pendaftaran::create([
                'jadwal_pendaftaran_id' => $jadwalPendaftaranAktif->id,
                'biodata_calon_siswa_id' => $BiodataCalonSiswa->id,
                'status_aktif' => true
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
}
