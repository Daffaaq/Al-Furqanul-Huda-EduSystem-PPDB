<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;

class PrestasiHelper
{
    public static function hitungNilaiAkhir(float $nilaiAkademik, array $prestasiList, bool $statusDiskualifikasi = false): float
    {
        if ($statusDiskualifikasi) {
            // Kalau diskualifikasi, nilai akhir langsung 0
            return 0;
        }
        
        $totalPoin = 0;
        foreach ($prestasiList as $prestasi) {
            $jumlah = $prestasi['jumlah'] ?? 0;
            $poin = $prestasi['poin_per_prestasi'] ?? 0;
            $totalPoin += $jumlah * $poin;
        }

        $periode = DB::table('periodes')->where('status_periode', 1)->first();

        if (!$periode) {
            $maxNonAkademik = 300;
            $bobotAkademik = 0.6;
            $bobotNonAkademik = 0.4;
        } else {
            $bobot = DB::table('bobot_pendaftarans')->where('periode_id', $periode->id)->first();

            $bobotAkademik = ($bobot->bobot_akademik ?? 60) / 100;
            $bobotNonAkademik = ($bobot->bobot_non_akademik ?? 40) / 100;

            $poinPrestasi = DB::table('prestasis')
                ->join('kategori_prestasis', 'prestasis.kategori_prestasi_id', '=', 'kategori_prestasis.id')
                ->where('kategori_prestasis.periode_id', $periode->id)
                ->orderByDesc('prestasis.point_prestasi')
                ->pluck('prestasis.point_prestasi')
                ->toArray();

            $top3Poin = array_slice($poinPrestasi, 0, 3);
            $maxNonAkademik = array_sum($top3Poin);
        }

        $nilaiNonAkademik = $maxNonAkademik > 0 ? ($totalPoin / $maxNonAkademik) * 100 : 0;

        $nilaiAkhir = ($nilaiAkademik * $bobotAkademik) + ($nilaiNonAkademik * $bobotNonAkademik);

        return round($nilaiAkhir, 2);
    }
}
