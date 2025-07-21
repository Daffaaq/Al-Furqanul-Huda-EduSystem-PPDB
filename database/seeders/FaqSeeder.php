<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil salah satu ID periode aktif (atau pertama)
        $periodeId = DB::table('periodes')->where('status_periode', 1)->value('id');

        if (!$periodeId) {
            $this->command->warn('Tidak ada data periode, seeder MaPelSeleksiSeeder dilewati.');
            return;
        }
        Faq::create([
            'question' => 'Apa saja syarat pendaftaran?',
            'answer' => '<ol class="list-decimal list-inside"><li>Scan PDF SKL/Transkrip Nilai Akhir</li><li>Scan PDF Prestasi (Opsional)</li></ol>',
            'order' => 1,
            'periode_id' => $periodeId
        ]);

        Faq::create([
            'question' => 'Apakah pendaftaran dibuka sepanjang tahun?',
            'answer' => 'Pendaftaran dibuka berdasarkan gelombang yang diumumkan melalui situs web resmi kami.',
            'order' => 2,
            'periode_id' => $periodeId
        ]);

        Faq::create([
            'question' => 'Bagaimana cara mengikuti seleksi di Al Furqanul Huda International Boarding School?',
            'answer' => 'Setelah pendaftaran, calon siswa Untuk Segera Upload Nilai dan Prestasi(Opsional) di
                            Dashboard masing-masing calon siswa',
            'order' => 3,
            'periode_id' => $periodeId
        ]);

        Faq::create([
            'question' => 'Bagaimana cara melakukan pendaftaran?',
            'answer' => '<ol class="list-decimal list-inside">
                <li>Calon Siswa Mendaftar untuk Masuk Ke Halaman Dashboard</li>
                <li>Calon Siswa Mengisi Biodata dengan tenggat waktu yang ditentukan. Jika terlambat, akan otomatis gagal.</li>
                <li>Setelah mengisi Biodata, calon siswa dipersilahkan untuk mengisi Nilai yang telah kami tentukan dan juga mengisi Prestasi jika ada.</li>
                <li>Calon Siswa Menunggu Hasil Seleksi.</li>
                <li>Jika Calon siswa dinyatakan Lulus, segera lakukan verifikasi. Jika tidak melakukan verifikasi, dinyatakan gagal.</li>
                <li>Jika Calon siswa dinyatakan lulus dan sudah verifikasi, tidak boleh mengundurkan diri dengan alasan apapun.</li>
                <li>Jika Calon siswa dinyatakan tidak lulus, mereka diberikan pilihan untuk mengikuti tahap selanjutnya atau tidak. Jika mengikuti, mereka hanya menunggu tahap selanjutnya dibuka dan tidak wajib mengisi ulang nilai dan prestasi, karena semua akan terekap. Jika tidak, data calon siswa akan dinyatakan gagal dan diarsipkan di sistem.</li>
                <li>Jika calon siswa pada gelombang terakhir tidak lulus, tidak ada pilihan untuk mengikuti tahap selanjutnya karena itu adalah gelombang terakhir.</li>
                <li>Jika calon siswa dinyatakan cadangan, mereka diberikan pilihan untuk mengikuti tahapan selanjutnya atau tidak. Jika tidak, data calon siswa akan dinyatakan gagal dan diarsipkan di sistem.</li>
                <li>Jika calon siswa pada tahap terakhir dinyatakan cadangan, mereka hanya berharap pada calon siswa yang tidak menekan verifikasi. Jumlah cadangan harus sama dengan jumlah siswa yang tidak menekan verifikasi pada tahap terakhir.</li>
            </ol>',
            'order' => 4,
            'periode_id' => $periodeId
        ]);
    }
}
