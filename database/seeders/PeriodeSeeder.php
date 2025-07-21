<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PeriodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('periodes')->insert([
            [
                'nama_periode' => 'Periode 2025/2026',
                'status_periode' => 1, // Aktif
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_periode' => 'Periode 2024/2025',
                'status_periode' => 0, // Aktif
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_periode' => 'Periode 2023/2024',
                'status_periode' => 0, // Tidak aktif
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
