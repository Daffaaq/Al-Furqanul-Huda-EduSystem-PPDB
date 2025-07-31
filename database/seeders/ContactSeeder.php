<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // kontaks
        DB::table('kontaks')->insert([
            'alamat' => 'Jl. Pendidikan No.123, Jakarta Selatan',
            'telepon' => '(021) 123-4567',
            'whatsapp' => '+6281234567890',
            'email' => 'info@alfurqanulhuda.sch.id',
            'latitude' => -6.208763,
            'longitude' => 106.845130,
            'facebook' => 'https://www.facebook.com/',
            'instagram' => 'https://www.instagram.com/',
            'youtube' => 'https://www.youtube.com/',
            'tiktok' => 'https://www.tiktok.com/',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Jam Operasional

        $data = [
            ['hari' => 'Senin', 'buka' => '08:00:00', 'tutup' => '16:00:00', 'tutup_full' => false],
            ['hari' => 'Selasa', 'buka' => '08:00:00', 'tutup' => '16:00:00', 'tutup_full' => false],
            ['hari' => 'Rabu', 'buka' => '08:00:00', 'tutup' => '16:00:00', 'tutup_full' => false],
            ['hari' => 'Kamis', 'buka' => '08:00:00', 'tutup' => '16:00:00', 'tutup_full' => false],
            ['hari' => 'Jumat', 'buka' => '08:00:00', 'tutup' => '16:00:00', 'tutup_full' => false],
            ['hari' => 'Sabtu', 'buka' => '09:00:00', 'tutup' => '13:00:00', 'tutup_full' => false],
            ['hari' => 'Minggu', 'buka' => null, 'tutup' => null, 'tutup_full' => true],
        ];

        foreach ($data as $jam) {
            DB::table('jam_operasionals')->insert([
                'hari' => $jam['hari'],
                'buka' => $jam['buka'],
                'tutup' => $jam['tutup'],
                'tutup_full' => $jam['tutup_full'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
