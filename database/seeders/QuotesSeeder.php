<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class QuotesSeeder extends Seeder
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

        $quotes = [
            [
                'quotes' => 'Barang siapa yang menempuh jalan untuk mencari ilmu, maka Allah akan mudahkan baginya jalan menuju surga.',
                'author' => 'HR. Muslim',
            ],
            [
                'quotes' => 'Sesungguhnya Allah akan meninggikan orang-orang yang beriman dan orang-orang yang diberi ilmu beberapa derajat.',
                'author' => 'QS. Al-Mujadila: 11',
            ],
            [
                'quotes' => 'Ilmu tanpa amal adalah kegilaan, dan amal tanpa ilmu adalah kesia-siaan.',
                'author' => 'Imam Al-Ghazali',
            ],
            [
                'quotes' => 'Menuntut ilmu itu wajib bagi setiap muslim.',
                'author' => 'HR. Ibnu Majah',
            ],
            [
                'quotes' => 'Jika engkau menginginkan dunia, maka dengan ilmu. Jika engkau menginginkan akhirat, maka dengan ilmu.',
                'author' => 'Imam Syafi\'i',
            ],
            [
                'quotes' => 'Tuntutlah ilmu dari buaian hingga liang lahat.',
                'author' => 'HR. Al-Baihaqi',
            ],
            [
                'quotes' => 'Para ulama adalah pewaris para nabi.',
                'author' => 'HR. Abu Dawud',
            ],
            [
                'quotes' => 'Satu hari bersama orang berilmu lebih aku sukai daripada beribadah seribu malam.',
                'author' => 'Imam Syafi\'i',
            ],
            [
                'quotes' => 'Tidak akan pernah kenyang orang yang berilmu dari ilmu sampai dia masuk surga.',
                'author' => 'HR. Tirmidzi',
            ],
            [
                'quotes' => 'Perjalanan untuk mencari ilmu adalah jihad di jalan Allah.',
                'author' => 'HR. Thabrani',
            ],
            [
                'quotes' => 'Ilmu itu lebih utama dari harta. Ilmu menjaga kamu, sedang kamu menjaga harta.',
                'author' => 'Ali bin Abi Thalib',
            ],
            [
                'quotes' => 'Dengan ilmu, hidup menjadi mudah. Dengan agama, hidup menjadi terarah.',
                'author' => 'Anonim',
            ],
            [
                'quotes' => 'Orang yang bodoh akan tetap menjadi musuh bagi dirinya sendiri, walau tampak diam.',
                'author' => 'Imam Ali bin Abi Thalib',
            ],
            [
                'quotes' => 'Jadilah orang yang berilmu, atau penuntut ilmu, atau pendengar, atau penyuka ilmu. Dan jangan jadi yang kelima, niscaya kamu akan celaka.',
                'author' => 'HR. Thabrani',
            ]
        ];


        foreach ($quotes as $quote) {
            DB::table('quotes')->insert([
                'periode_id' => $periode->id,
                'quotes' => $quote['quotes'],
                'author' => $quote['author'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
