<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class JadwalPendaftaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil periode aktif dari tabel periodes
        $periode = DB::table('periodes')->where('status_periode', 1)->first();

        if (!$periode) {
            $this->command->info('Tidak ada periode aktif untuk mengisi jadwal pendaftaran.');
            return;
        }

        $gelombangList = ['Gelombang 1', 'Gelombang 2', 'Gelombang 3'];
        $startDate = Carbon::now(); // Gelombang 1 mulai 2 hari dari sekarang

        foreach ($gelombangList as $index => $gelombang) {
            $tanggal_mulai_jadwal_pendaftaran = $startDate->copy();
            $deadline_biodata_calon_siswa = $tanggal_mulai_jadwal_pendaftaran->copy()->addDays(12);
            $tanggal_mulai_verifikasi = $deadline_biodata_calon_siswa->copy()->addDay();
            $deadline_upload_pendaftaran = $tanggal_mulai_verifikasi->copy()->addDays(7);
            $tanggal_selesai_verifikasi = $deadline_upload_pendaftaran->copy()->addDays(7);
            $tanggal_selesai_jadwal_pendaftaran = $tanggal_mulai_jadwal_pendaftaran->copy()->addMonth();
            $pengumuman_hasil_seleksi = $tanggal_selesai_jadwal_pendaftaran->copy()->addDay();

            $status = ($index == 0) ? 'Opened' : 'Ongoing';

            DB::table('jadwal_pendaftarans')->insert([
                'nama_jadwal_pendaftaran' => 'Pendaftaran 2025/2026',
                'tanggal_mulai_jadwal_pendaftaran' => $tanggal_mulai_jadwal_pendaftaran,
                'tanggal_selesai_jadwal_pendaftaran' => $tanggal_selesai_jadwal_pendaftaran,
                'tanggal_mulai_verifikasi' => $tanggal_mulai_verifikasi,
                'tanggal_selesai_verifikasi' => $tanggal_selesai_verifikasi,
                'gelombang_pendaftaran' => $gelombang,
                'deadline_biodata_calon_siswa' => $deadline_biodata_calon_siswa,
                'deadline_upload_pendaftaran' => $deadline_upload_pendaftaran,
                'pengumuman_hasil_seleksi' => $pengumuman_hasil_seleksi,
                'kuota_akun' => 20,
                'kuota_pendaftaran' => 20,
                'kuota_penerimaan' => 10,
                'status_jadwal_pendaftaran' => $status,
                'tampilkan_perangkingan' => false,
                'periode_id' => $periode->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            // Set tanggal mulai gelombang berikutnya: 1 bulan setelah pengumuman sebelumnya
            $startDate = $pengumuman_hasil_seleksi->copy()->addMonth();
        }
    }
}
