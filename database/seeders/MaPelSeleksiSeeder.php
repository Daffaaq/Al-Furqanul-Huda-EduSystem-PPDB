<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class MaPelSeleksiSeeder extends Seeder
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

        // Isi data mata pelajaran seleksi
        $mapels = [
            'Matematika',
            'Bahasa Indonesia',
            'Bahasa Inggris',
            'IPA',
        ];

        foreach ($mapels as $mapel) {
            DB::table('mata_pelajaran_seleksis')->insert([
                'nama_mata_pelajaran_seleksi' => $mapel,
                'periode_id' => $periodeId,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
