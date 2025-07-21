<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;

trait PendaftaranFilterTrait
{
    public function applyPendaftaranFilters($request, $query)
    {
        $adaFilter = false;

        if ($request->filled('periode_id')) {
            $query->where('biodata_calon_siswas.periode_id', $request->periode_id);
            $adaFilter = true;
        }

        if ($request->filled('gelombang')) {
            $query->where('jadwal_pendaftarans.gelombang_pendaftaran', $request->gelombang);
            $adaFilter = true;
        }

        // Jika tidak ada filter dari user
        if (!$adaFilter) {
            $openedExists = DB::table('jadwal_pendaftarans')
                ->where('status_jadwal_pendaftaran', 'Opened')
                ->exists();

            if ($openedExists) {
                // Jika ada yang status-nya "Opened", hanya tampilkan yang itu
                $query->where('jadwal_pendaftarans.status_jadwal_pendaftaran', 'Opened');
            }
            // Jika tidak ada "Opened", tampilkan semua (tidak di-filter)
        }

        return $query;
    }
}
