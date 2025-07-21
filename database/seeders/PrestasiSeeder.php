<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PrestasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil periode aktif
        $periode = DB::table('periodes')->where('status_periode', 1)->first();

        if (!$periode) {
            $this->command->error('Tidak ada periode aktif ditemukan!');
            return;
        }

        $kategoriList = [
            'Internasional' => 100,
            'Nasional' => 90,
            'Provinsi' => 80,
            'Kabupaten/Kota' => 70
        ];

        $peringkatList = [
            'Juara 1' => 0,
            'Juara 2' => 5,
            'Juara 3' => 10,
            'Harapan 1' => 15,
            'Harapan 2' => 20,
            'Harapan 3' => 25,
        ];

        foreach ($kategoriList as $kategori => $basePoint) {
            // Insert kategori_prestasi
            $kategoriId = DB::table('kategori_prestasis')->insertGetId([
                'nama_kategori_prestasi' => $kategori,
                'periode_id' => $periode->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            // Insert prestasi untuk kategori ini
            foreach ($peringkatList as $peringkat => $pengurang) {
                DB::table('prestasis')->insert([
                    'kategori_prestasi_id' => $kategoriId,
                    'nama_prestasi' => $peringkat,
                    'point_prestasi' => max($basePoint - $pengurang, 0),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }

        $this->command->info('Data prestasi dan kategori berhasil dimasukkan.');
    }
}
