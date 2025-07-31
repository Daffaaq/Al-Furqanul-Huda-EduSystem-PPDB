<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use AzisHapidin\IndoRegion\IndoRegion;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            RoleAndPermissionSeeder::class,
            MenuGroupSeeder::class,
            MenuItemSeeder::class,
            PeriodeSeeder::class,
            MaPelSeleksiSeeder::class,
            JadwalPendaftaranSeeder::class,
            PrestasiSeeder::class,
            BobotPendaftaranSeeder::class,
            IndoRegionSeeder::class,
            FaqSeeder::class,
            QuotesSeeder::class,
            ContactSeeder::class
        ]);
    }
}
