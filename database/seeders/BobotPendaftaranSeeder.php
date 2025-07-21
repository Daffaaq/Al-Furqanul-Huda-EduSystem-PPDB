<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BobotPendaftaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $periodes = DB::table('periodes')->orderBy('id')->get();

        // Bobot preset, bisa ditambah jika periodenya lebih banyak
        $bobotPresets = [
            ['bobot_akademik' => 60, 'bobot_non_akademik' => 40],
            ['bobot_akademik' => 70, 'bobot_non_akademik' => 30],
            ['bobot_akademik' => 80, 'bobot_non_akademik' => 20],
        ];

        foreach ($periodes as $index => $periode) {
            $preset = $bobotPresets[$index] ?? ['bobot_akademik' => 50, 'bobot_non_akademik' => 50]; // default kalau lebih dari 3

            DB::table('bobot_pendaftarans')->insert([
                'bobot_akademik' => $preset['bobot_akademik'],
                'bobot_non_akademik' => $preset['bobot_non_akademik'],
                'periode_id' => $periode->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
