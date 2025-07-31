<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePendaftaranCalonSiswaRequest;
use App\Models\BiodataCalonSiswa;
use App\Models\District;
use App\Models\JadwalPendaftaran;
use App\Models\KategoriPrestasi;
use App\Models\MataPelajaranSeleksi;
use App\Models\NilaiAkademikPendaftar;
use App\Models\Pendaftaran;
use App\Models\Prestasi;
use App\Models\PrestasiPendaftar;
use App\Models\Province;
use App\Models\Regency;
use App\Models\SlotKosong;
use App\Models\User;
use App\Models\Village;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index()
    {
        $periode = DB::table('periodes')->where('status_periode', 1)->first();

        if ($periode) {
            $totalPrestasi = Prestasi::whereHas('kategori_prestasi', function ($query) use ($periode) {
                $query->where('periode_id', $periode->id);
            })->count();

            $totalKategoriSeleksi = KategoriPrestasi::where('periode_id', $periode->id)->count();
            $totalSeleksiMataPelajaran = MataPelajaranSeleksi::where('periode_id', $periode->id)->count();
            $totalPendaftar = BiodataCalonSiswa::where('periode_id', $periode->id)->count();
            $totalTahapSeleksi = JadwalPendaftaran::where('periode_id', $periode->id)->count();



            $now = now();

            // Ambil jadwal yang sedang aktif
            $jadwalPendaftaranAktif = DB::table('jadwal_pendaftarans')
                ->where('periode_id', $periode->id)
                ->where('tanggal_mulai_jadwal_pendaftaran', '<=', $now)
                ->where('tanggal_selesai_jadwal_pendaftaran', '>=', $now->format('Y-m-d'))
                ->first();

            $biodata = BiodataCalonSiswa::where('user_id', auth()->id())
                ->where('periode_id', $periode->id)
                ->first();

            $pendaftaran = optional($biodata)->id
                ? \App\Models\Pendaftaran::where('biodata_calon_siswa_id', $biodata->id)->latest()->first()
                : null;

            $fieldsToCheck = [
                'nama_calon_siswa',
                'email_calon_siswa',
                'jenis_kelamin_calon_siswa',
                'tanggal_lahir_calon_siswa',
                'tempat_tanggal_lahir_calon_siswa',
                'alamat_rumah_calon_siswa',
                'agama_calon_siswa',
                'nomor_telepon_calon_siswa',
                'user_id',
                'periode_id'
            ];

            $biodataIncomplete = false;

            if (!$biodata || collect($fieldsToCheck)->contains(fn($field) => is_null($biodata->$field))) {
                $biodataIncomplete = true;
            }
            $bobot = \App\Models\BobotPendaftaran::where('periode_id', $periode->id)->first();
            if (!$bobot) {
                $bobot = (object)[
                    'bobot_akademik' => 0,
                    'bobot_non_akademik' => 0,
                ];
            }
            $jadwal = JadwalPendaftaran::where('periode_id', $periode->id)
                ->where('status_jadwal_pendaftaran', 'Opened')
                ->first();

            $totalKelulusan = Pendaftaran::where('status_final', 'Lolos')->count();

            $totalDiskualifikasi = Pendaftaran::where('status_diskualifikasi', '1')->count();

            $totalCadangan = Pendaftaran::where('status_cadangan', '1')->count();

            $slotKosong = SlotKosong::get()->count();


            if ($jadwal) {
                $publishStatus = $jadwal->tampilkan_perangkingan ? 'published' : 'not_published';
            }
        } else {
            // Jika tidak ada periode aktif, set semua total ke 0 atau nilai default lain
            $totalPrestasi = 0;
            $totalKategoriSeleksi = 0;
            $totalSeleksiMataPelajaran = 0;
            $totalPendaftar = 0;
            $totalTahapSeleksi = 0;
            $jadwal = null;
            $publishStatus = null;
        }

        return view('home', compact(
            'totalPrestasi',
            'totalSeleksiMataPelajaran',
            'totalKategoriSeleksi',
            'totalPendaftar',
            'totalTahapSeleksi',
            'biodataIncomplete',
            'bobot',
            'pendaftaran',
            'publishStatus',
            'jadwal',
            'totalKelulusan',
            'totalDiskualifikasi',
            'totalCadangan',
            'slotKosong'
        ));
    }

    public function generateDummyPendaftar()
    {
        $faker = Faker::create('id_ID');

        // Cek periode aktif
        $periode = DB::table('periodes')->where('status_periode', 1)->first();
        if (!$periode) {
            return response()->json(['success' => false, 'message' => 'Periode pendaftaran belum dibuka']);
        }

        $now = now();

        // Cek jadwal aktif
        $jadwalAktif = DB::table('jadwal_pendaftarans')
            ->where('periode_id', $periode->id)
            ->where('status_jadwal_pendaftaran', 'Opened')
            ->first();

        if (!$jadwalAktif) {
            return response()->json(['success' => false, 'message' => 'Jadwal pendaftaran belum dibuka']);
        }

        // Hitung jumlah dummy yang perlu dibuat
        $jumlahPendaftar = DB::table('pendaftarans')->where('jadwal_pendaftaran_id', $jadwalAktif->id)->count();
        $jumlahDummy = $jadwalAktif->kuota_akun - $jumlahPendaftar;

        if ($jumlahDummy <= 0) {
            return response()->json(['success' => false, 'message' => 'Jadwal pendaftaran sudah penuh']);
        }

        $mataPelajaran = MataPelajaranSeleksi::where('periode_id', $periode->id)->get();
        $daftarPrestasi = Prestasi::whereHas('kategori_prestasi', function ($q) use ($periode) {
            $q->where('periode_id', $periode->id);
        })->get();

        for ($i = 0; $i < $jumlahDummy; $i++) {
            // === Biodata dasar ===
            $firstName = $faker->unique()->firstName();
            $lastName = $faker->unique()->lastName();
            $namePart = $faker->randomElement([$firstName, $lastName]);

            $safeEmail = $faker->safeEmail();
            $domain = substr($safeEmail, strpos($safeEmail, '@') + 1);
            $twoDigits = str_pad($faker->numberBetween(1, 99), 2, '0', STR_PAD_LEFT);
            $email = strtolower($namePart . $twoDigits . '@' . $domain);

            $tanggalLahir = $faker->dateTimeBetween('2008-07-01', '2009-07-31')->format('Y-m-d');
            $nomorTelepon = '62' . $faker->numerify(str_repeat('#', rand(10, 13)));

            $province = Province::inRandomOrder()->first();
            $regency = Regency::where('province_id', $province->id)->inRandomOrder()->first();
            $district = District::where('regency_id', $regency->id)->inRandomOrder()->first();
            $village = Village::where('district_id', $district->id)->inRandomOrder()->first();
            $alamatLengkap = $faker->streetAddress() . ', ' . $village->name . ', ' . $district->name . ', ' . $regency->name . ', ' . $province->name;

            // === Create User ===
            $user = User::create([
                'name' => $firstName,
                'email' => $email,
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]);

            $user->assignRole('calon-siswa');

            // === Create Biodata ===
            $biodata = BiodataCalonSiswa::create([
                'user_id' => $user->id,
                'nama_calon_siswa' => "$firstName $lastName",
                'tanggal_lahir_calon_siswa' => $tanggalLahir,
                'tempat_tanggal_lahir_calon_siswa' => $regency->name,
                'alamat_rumah_calon_siswa' => $alamatLengkap,
                'agama_calon_siswa' => 'Islam',
                'nomor_telepon_calon_siswa' => $nomorTelepon,
                'jenis_kelamin_calon_siswa' => $faker->randomElement(['Laki-laki', 'Perempuan']),
                'email_calon_siswa' => $user->email,
                'periode_id' => $periode->id,
            ]);

            // === Create Pendaftaran ===
            $pendaftaran = Pendaftaran::create([
                'jadwal_pendaftaran_id' => $jadwalAktif->id,
                'biodata_calon_siswa_id' => $biodata->id,
                'status_aktif' => true,
            ]);

            // === Insert Nilai Akademik ===
            foreach ($mataPelajaran as $mapel) {
                NilaiAkademikPendaftar::create([
                    'pendaftaran_id' => $pendaftaran->id,
                    'mata_pelajaran_seleksi_id' => $mapel->id,
                    'nilai' => $faker->numberBetween(80, 100),
                    'status' => 'Pending',
                ]);
            }

            // === Insert minimal total 3 Prestasi ===
            $prestasiDipilih = collect();
            $totalJumlahPrestasi = 0;

            $availablePrestasi = $daftarPrestasi->shuffle(); // acak urutan

            foreach ($availablePrestasi as $prestasi) {
                if ($totalJumlahPrestasi >= 3) break;

                // Tentukan jumlah prestasi yang bisa diinput (jangan sampai melebihi 3)
                $maksimal = 3 - $totalJumlahPrestasi;
                $jumlah = $faker->numberBetween(1, $maksimal);

                PrestasiPendaftar::create([
                    'pendaftaran_id' => $pendaftaran->id,
                    'prestasi_id' => $prestasi->id,
                    'jumlah_prestasi' => $jumlah,
                    'status' => 'Pending',
                    'dokumen_prestasi_pendukung' => null,
                    'file_type' => null,
                ]);

                $totalJumlahPrestasi += $jumlah;
            }
        }

        $newTotalPendaftar = DB::table('pendaftarans')
            ->where('jadwal_pendaftaran_id', $jadwalAktif->id)
            ->count();

        return response()->json([
            'success' => true,
            'message' => 'Dummy pendaftar sejumlah ' . $jumlahDummy . ' berhasil dibuat lengkap dengan nilai dan prestasi',
            'totalPendaftar' => $newTotalPendaftar,
        ]);
    }

    // public function publishRanking()
    // {
    //     $periode = DB::table('periodes')->where('status_periode', 1)->first();
    //     if (!$periode) {
    //         return response()->json(['message' => 'Periode belum aktif'], 400);
    //     }

    //     $jadwal = JadwalPendaftaran::where('status_jadwal_pendaftaran', 'Opened')
    //         ->where('periode_id', $periode->id)
    //         ->first();

    //     if (!$jadwal) {
    //         return response()->json(['message' => 'Jadwal pendaftaran belum dibuka'], 400);
    //     }

    //     if ($jadwal->tampilkan_perangkingan) {
    //         return response()->json(['message' => 'Perangkingan sudah dipublikasikan'], 400);
    //     }

    //     // Ambil semua pendaftaran pada jadwal ini
    //     $pendaftarans = $jadwal->pendaftarans;

    //     if ($pendaftarans->count() === 0) {
    //         return response()->json(['message' => 'Belum ada pendaftaran pada jadwal ini'], 400);
    //     }

    //     DB::beginTransaction();

    //     try {
    //         foreach ($pendaftarans as $pendaftaran) {
    //             // Skip kalau sudah final
    //             if ($pendaftaran->is_final) {
    //                 continue;
    //             }

    //             // Cek semua nilai akademik sudah 'Accept'
    //             $nilaiAkademik = DB::table('nilai_akademik_pendaftars')
    //                 ->where('pendaftaran_id', $pendaftaran->id)
    //                 ->pluck('status');

    //             if ($nilaiAkademik->contains(fn($status) => $status !== 'Accept')) {
    //                 DB::rollBack();
    //                 return response()->json([
    //                     'message' => 'Terdapat pendaftar dengan nilai akademik yang belum di-ACC',
    //                     'nama' => $pendaftaran->biodataCalonSiswa->nama_calon_siswa ?? 'Unknown'
    //                 ], 400);
    //             }

    //             // Generate nomor pendaftaran
    //             $tahun = Carbon::now()->format('Y');
    //             $prefix = 'AFH-' . $tahun . '-';

    //             $maxRetry = 5;
    //             $attempt = 0;
    //             $nomorPendaftaran = null;

    //             do {
    //                 $last = Pendaftaran::whereYear('created_at', $tahun)
    //                     ->whereNotNull('nomer_pendaftaran')
    //                     ->lockForUpdate()
    //                     ->orderByDesc('nomer_pendaftaran')
    //                     ->first();

    //                 $lastNumber = 0;
    //                 if ($last) {
    //                     $parts = explode('-', $last->nomer_pendaftaran);
    //                     $lastNumber = (int) end($parts);
    //                 }

    //                 $newNumber = $lastNumber + 1 + $attempt;
    //                 $noUrut = str_pad($newNumber, 5, '0', STR_PAD_LEFT);
    //                 $nomorPendaftaran = $prefix . $noUrut;

    //                 $exists = Pendaftaran::where('nomer_pendaftaran', $nomorPendaftaran)->exists();

    //                 if ($exists) {
    //                     $attempt++;
    //                 } else {
    //                     break;
    //                 }
    //             } while ($attempt < $maxRetry);

    //             if ($exists) {
    //                 DB::rollBack();
    //                 return response()->json([
    //                     'message' => 'Gagal membuat nomor pendaftaran yang unik. Silakan coba lagi.',
    //                 ], 400);
    //             }

    //             // Finalisasi pendaftaran
    //             $pendaftaran->update([
    //                 'is_final' => true,
    //                 'nomer_pendaftaran' => $nomorPendaftaran,
    //                 'tanggal_pendaftaran' => now(),
    //             ]);
    //         }

    //         // Setelah semua lolos finalisasi
    //         $jadwal->tampilkan_perangkingan = true;
    //         $jadwal->save();

    //         DB::commit();

    //         return response()->json(['message' => 'Perangkingan berhasil dipublikasikan dan semua pendaftar telah didaftarkan.']);
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return response()->json([
    //             'message' => 'Gagal mempublikasikan perangkingan.',
    //             'error' => $e->getMessage()
    //         ], 500);
    //     }
    // }

    public function publishRanking()
    {
        $periode = DB::table('periodes')->where('status_periode', 1)->first();
        if (!$periode) {
            return response()->json(['message' => 'Periode belum aktif'], 400);
        }

        $jadwal = JadwalPendaftaran::where('status_jadwal_pendaftaran', 'Opened')
            ->where('periode_id', $periode->id)
            ->first();

        if (!$jadwal) {
            return response()->json(['message' => 'Jadwal pendaftaran belum dibuka'], 400);
        }

        if ($jadwal->tampilkan_perangkingan) {
            return response()->json(['message' => 'Perangkingan sudah dipublikasikan'], 400);
        }

        $pendaftarans = $jadwal->pendaftarans;

        if ($pendaftarans->count() === 0) {
            return response()->json(['message' => 'Belum ada pendaftaran pada jadwal ini'], 400);
        }

        DB::beginTransaction();

        try {
            // Finalisasi pendaftaran yang belum final dan cek nilai akademik
            foreach ($pendaftarans as $pendaftaran) {
                if ($pendaftaran->is_final) {
                    continue;
                }

                $nilaiAkademik = DB::table('nilai_akademik_pendaftars')
                    ->where('pendaftaran_id', $pendaftaran->id)
                    ->pluck('status');

                if ($nilaiAkademik->contains(fn($status) => $status !== 'Accept')) {
                    DB::rollBack();
                    return response()->json([
                        'message' => 'Terdapat pendaftar dengan nilai akademik yang belum di-ACC',
                        'nama' => $pendaftaran->biodataCalonSiswa->nama_calon_siswa ?? 'Unknown'
                    ], 400);
                }

                // Generate nomor pendaftaran
                $tahun = Carbon::now()->format('Y');
                $prefix = 'AFH-' . $tahun . '-';

                $maxRetry = 5;
                $attempt = 0;
                $nomorPendaftaran = null;

                do {
                    $last = Pendaftaran::whereYear('created_at', $tahun)
                        ->whereNotNull('nomer_pendaftaran')
                        ->lockForUpdate()
                        ->orderByDesc('nomer_pendaftaran')
                        ->first();

                    $lastNumber = 0;
                    if ($last) {
                        $parts = explode('-', $last->nomer_pendaftaran);
                        $lastNumber = (int) end($parts);
                    }

                    $newNumber = $lastNumber + 1 + $attempt;
                    $noUrut = str_pad($newNumber, 5, '0', STR_PAD_LEFT);
                    $nomorPendaftaran = $prefix . $noUrut;

                    $exists = Pendaftaran::where('nomer_pendaftaran', $nomorPendaftaran)->exists();

                    if ($exists) {
                        $attempt++;
                    } else {
                        break;
                    }
                } while ($attempt < $maxRetry);

                if ($exists) {
                    DB::rollBack();
                    return response()->json([
                        'message' => 'Gagal membuat nomor pendaftaran yang unik. Silakan coba lagi.',
                    ], 400);
                }

                $pendaftaran->update([
                    'is_final' => true,
                    'nomer_pendaftaran' => $nomorPendaftaran,
                    'tanggal_pendaftaran' => now(),
                ]);
            }

            // Hitung nilai akhir setiap pendaftar (asumsi kamu punya metode untuk ini)
            $ranking = $pendaftarans->map(function ($pendaftaran) {
                $nilaiAkademik = DB::table('nilai_akademik_pendaftars')
                    ->where('pendaftaran_id', $pendaftaran->id)
                    ->where('status', 'Accept')
                    ->avg('nilai') ?? 0;

                $prestasiList = DB::table('prestasi_pendaftars')
                    ->where('pendaftaran_id', $pendaftaran->id)
                    ->get()
                    ->map(fn($p) => [
                        'jumlah' => $p->jumlah_prestasi,
                        'poin_per_prestasi' => $p->point_prestasi ?? 0,
                    ])
                    ->toArray();

                $nilaiAkhir = \App\Helpers\PrestasiHelper::hitungNilaiAkhir(
                    $nilaiAkademik,
                    $prestasiList,
                    $pendaftaran->status_diskualifikasi ?? false
                );


                return (object) [
                    'pendaftaran' => $pendaftaran,
                    'nilaiAkhir' => $nilaiAkhir,
                ];
            });

            $kuota = $jadwal->kuota_penerimaan;

            $ranking = $ranking->sortByDesc('nilaiAkhir')->values();

            // Ambil nilai batas (nilai di posisi kuota)
            $nilaiBatas = $ranking->count() >= $kuota ? $ranking[$kuota - 1]->nilaiAkhir : null;

            // Cek apakah ada nilai sama dengan nilai batas di luar kuota
            $adaNilaiSamaDiBawah = $ranking->slice($kuota)->contains(fn($item) => $item->nilaiAkhir == $nilaiBatas);

            $diterima = collect();
            $cadangan = collect();
            $tidakLolos = collect();

            if ($nilaiBatas === null) {
                // Jika jumlah pendaftar kurang dari kuota, semua lolos
                $diterima = $ranking;
            } else {
                if ($adaNilaiSamaDiBawah) {
                    // Jika ada nilai sama dengan nilai batas di luar kuota
                    foreach ($ranking as $item) {
                        if ($item->nilaiAkhir > $nilaiBatas) {
                            $diterima->push($item);
                        } elseif ($item->nilaiAkhir == $nilaiBatas) {
                            $cadangan->push($item);
                        } else {
                            $tidakLolos->push($item);
                        }
                    }
                } else {
                    // Tidak ada nilai sama di bawah batas, maka semua di posisi kuota dan atasnya lolos
                    foreach ($ranking as $index => $item) {
                        if ($index < $kuota) {
                            $diterima->push($item);
                        } else {
                            $tidakLolos->push($item);
                        }
                    }
                }
            }

            foreach ($diterima as $item) {
                $item->pendaftaran->update([
                    'status_final' => 'Lolos',
                    'status_cadangan' => false,
                ]);
            }

            foreach ($cadangan as $item) {
                $item->pendaftaran->update([
                    'status_final' => 'Tidak Lolos',
                    'status_cadangan' => true,
                ]);
            }

            foreach ($tidakLolos as $item) {
                $item->pendaftaran->update([
                    'status_final' => 'Tidak Lolos',
                    'status_cadangan' => false,
                ]);
            }

            // Tandai perangkingan sudah dipublikasikan
            $jadwal->tampilkan_perangkingan = true;
            $jadwal->save();

            DB::commit();

            return response()->json(['message' => 'Perangkingan berhasil dipublikasikan dan semua pendaftar telah didaftarkan.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Gagal mempublikasikan perangkingan.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function SettingBiodata()
    {
        $periode = DB::table('periodes')->where('status_periode', 1)->first();
        if (!$periode) {
            return response()->json(['message' => 'Periode belum aktif'], 400);
        }

        $jadwal = JadwalPendaftaran::where('status_jadwal_pendaftaran', 'Opened')
            ->where('periode_id', $periode->id)
            ->first();

        if (!$jadwal) {
            return response()->json(['message' => 'Jadwal pendaftaran belum dibuka'], 400);
        }

        // // Cek apakah semua pendaftaran di jadwal ini sudah final
        $totalPendaftaran = $jadwal->pendaftarans()->count();
        // $totalFinal = $jadwal->pendaftarans()->where('is_final', true)->count();

        if ($totalPendaftaran == 0) {
            return response()->json(['message' => 'Belum ada pendaftaran pada jadwal ini'], 400);
        }

        // if ($totalPendaftaran !== $totalFinal) {
        //     return response()->json(['message' => 'Tidak semua pendaftaran sudah final'], 400);
        // }

        $jadwal->biodata_ditutup = !$jadwal->biodata_ditutup;

        $jadwal->save();

        $status = $jadwal->biodata_ditutup ? 'Ditutup' : 'Dibuka';

        return response()->json([
            'message' => 'Setting Biodata Berhasil Diubah (' . $status . ')'
        ]);
    }

    public function settingUploadNilai()
    {
        $periode = DB::table('periodes')->where('status_periode', 1)->first();
        if (!$periode) {
            return response()->json(['message' => 'Periode belum aktif'], 400);
        }

        $jadwal = JadwalPendaftaran::where('status_jadwal_pendaftaran', 'Opened')
            ->where('periode_id', $periode->id)
            ->first();

        if (!$jadwal) {
            return response()->json(['message' => 'Jadwal pendaftaran belum dibuka'], 400);
        }

        if ($jadwal->tampilkan_perangkingan) {
            return response()->json(['message' => 'Perangkingan sudah dipublikasikan'], 400);
        }

        // Cek apakah semua pendaftaran di jadwal ini sudah final
        $totalPendaftaran = $jadwal->pendaftarans()->count();
        $jumlahMapelSeleksi = MataPelajaranSeleksi::where('periode_id', $periode->id)->count();

        $acceptedNilaiAkademik = NilaiAkademikPendaftar::whereHas('pendaftaran', function ($query) use ($jadwal) {
            $query->where('jadwal_pendaftaran_id', $jadwal->id);
        })->where('status', 'Accept')->count();
        // $totalFinal = $jadwal->pendaftarans()->where('is_final', true)->count();

        if ($totalPendaftaran == 0) {
            return response()->json(['message' => 'Belum ada pendaftaran pada jadwal ini'], 400);
        }

        if ($acceptedNilaiAkademik !== $totalPendaftaran * $jumlahMapelSeleksi) {
            return response()->json(['message' => 'Terdapat nilai akademik yang belum memiliki status “Accept”.'], 400);
        }
        $jadwal->upload_ditutup = !$jadwal->upload_ditutup;
        $jadwal->save();

        $status = $jadwal->upload_ditutup ? 'Ditutup' : 'Dibuka';

        return response()->json(['message' => 'Setting Upload Nilai Berhasil Diubah (' . $status . ')']);
    }

    public function settingStatusDiterima()
    {
        $periode = DB::table('periodes')->where('status_periode', 1)->first();
        if (!$periode) {
            return response()->json(['message' => 'Periode belum aktif'], 400);
        }

        $jadwal = JadwalPendaftaran::where('status_jadwal_pendaftaran', 'Opened')
            ->where('periode_id', $periode->id)
            ->first();

        if (!$jadwal) {
            return response()->json(['message' => 'Jadwal pendaftaran belum dibuka'], 400);
        }

        if ($jadwal->tampilkan_perangkingan) {
            $updated = Pendaftaran::where('jadwal_pendaftaran_id', $jadwal->id)
                ->where('status_final', 'Lolos')
                ->update(['status_accept' => 'Accept']);

            if ($updated > 0) {
                return response()->json([
                    'message' => "Status 'Accept' berhasil diterapkan pada $updated pendaftar.",
                ]);
            } else {
                return response()->json([
                    'message' => "Tidak ada pendaftar dengan status 'Lolos' yang perlu diperbarui.",
                ]);
            }
        }

        return response()->json(['message' => 'Perangkingan belum dipublikasikan tidak dapat diubah'], 400);
    }


    public function moveToNextJadwal()
    {
        // 1. Ambil periode aktif
        $periode = DB::table('periodes')->where('status_periode', 1)->first();
        if (!$periode) {
            return response()->json(['message' => 'Periode belum aktif'], 400);
        }

        // 2. Ambil jadwal yang sedang 'Opened' di periode aktif
        $currentJadwal = JadwalPendaftaran::where('periode_id', $periode->id)
            ->where('status_jadwal_pendaftaran', 'Opened')
            ->first();

        if (!$currentJadwal) {
            return response()->json(['message' => 'Tidak ada jadwal yang sedang dibuka saat ini'], 400);
        }

        // Cek apakah semua pendaftaran di jadwal ini sudah final
        $totalPendaftaran = $currentJadwal->pendaftarans()->count();
        $totalFinal = $currentJadwal->pendaftarans()->where('is_final', true)->count();

        if ($totalPendaftaran == 0) {
            return response()->json(['message' => 'Belum ada pendaftaran pada jadwal ini'], 400);
        }

        if ($totalPendaftaran !== $totalFinal) {
            return response()->json(['message' => 'Tidak semua pendaftaran sudah final'], 400);
        }

        // 3. Hitung jumlah cadangan dari pendaftar di jadwal saat ini
        $jumlahCadangan = $currentJadwal->pendaftarans()
            ->where('status_cadangan', true)
            ->count();

        // hitung jumlah diskualifikasi dari pendaftar di jadwal saat ini
        $jumlahDiskualifikasi = $currentJadwal->pendaftarans()
            ->where('status_diskualifikasi', true)
            ->count();

        // 4. Cek apakah currentJadwal adalah jadwal terakhir di periode ini
        $lastJadwal = JadwalPendaftaran::where('periode_id', $periode->id)
            ->orderBy('tanggal_mulai_jadwal_pendaftaran', 'desc')
            ->first();

        $isLastJadwal = $currentJadwal->id === $lastJadwal->id;

        if ($isLastJadwal) {
            // 5A. Jika ini jadwal terakhir, tambahkan jumlah cadangan ke kuota_penerimaan dan jumlah diskualifikasi ke kuota_diskualifikasi
            $currentJadwal->kuota_penerimaan += $jumlahDiskualifikasi;
            $currentJadwal->kuota_penerimaan += $jumlahCadangan;
            $currentJadwal->status_jadwal_pendaftaran = 'Closed';
            $currentJadwal->save();

            return response()->json([
                'message' => "Jadwal sudah di gelombang terakhir. Kuota penerimaan ditambah $jumlahCadangan cadangan."
            ]);
        }

        // 5B. Jika masih ada jadwal selanjutnya, cari yang statusnya 'Ongoing' dan tanggalnya lebih besar dari sekarang
        $nextJadwal = JadwalPendaftaran::where('periode_id', $periode->id)
            ->where('status_jadwal_pendaftaran', 'Ongoing')
            ->where('tanggal_mulai_jadwal_pendaftaran', '>', $currentJadwal->tanggal_mulai_jadwal_pendaftaran)
            ->orderBy('tanggal_mulai_jadwal_pendaftaran', 'asc')
            ->first();

        if (!$nextJadwal) {
            return response()->json([
                'message' => 'Tidak ditemukan jadwal berikutnya dengan status Ongoing'
            ], 400);
        }

        // 6. Update status jadwal sekarang menjadi 'Closed'
        $currentJadwal->status_jadwal_pendaftaran = 'Closed';
        $currentJadwal->save();

        // 7. Update status jadwal berikutnya menjadi 'Opened'
        $nextJadwal->status_jadwal_pendaftaran = 'Opened';
        $nextJadwal->save();


        // 8. Simpan slot kosong berdasarkan jumlah cadangan ke jadwal berikutnya
        $nextJadwal->slotKosong()->updateOrCreate(
            ['jadwal_pendaftaran_id' => $nextJadwal->id],
            ['jumlah_kosong' => $jumlahCadangan]
        );

        return response()->json([
            'message' => "Berhasil pindah ke jadwal berikutnya: {$nextJadwal->gelombang_pendaftaran}. Jumlah cadangan dialihkan sebagai slot kosong: $jumlahCadangan."
        ]);
    }
}
