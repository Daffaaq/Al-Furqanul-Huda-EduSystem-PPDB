<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\PendaftaranFilterTrait;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class PengumumanController extends Controller
{
    use PendaftaranFilterTrait;
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:pengumuman.index')->only('index', 'list');
        $this->middleware('permission:pengumuman.create')->only('create', 'store');
        $this->middleware('permission:pengumuman.edit')->only('edit', 'update');
        $this->middleware('permission:pengumuman.destroy')->only('destroy');
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            $pendaftarans = DB::table('pendaftarans')
                ->leftJoin('biodata_calon_siswas', 'pendaftarans.biodata_calon_siswa_id', '=', 'biodata_calon_siswas.id')
                ->leftJoin('periodes', 'biodata_calon_siswas.periode_id', '=', 'periodes.id')
                ->leftJoin('jadwal_pendaftarans', 'pendaftarans.jadwal_pendaftaran_id', '=', 'jadwal_pendaftarans.id')
                ->where('is_final', true)
                ->select(
                    'pendaftarans.id',
                    'biodata_calon_siswas.nama_calon_siswa as nama_calon_siswa',
                    'pendaftarans.nomer_pendaftaran',
                    'pendaftarans.status_final',
                    'jadwal_pendaftarans.gelombang_pendaftaran',
                    'pendaftarans.status_accept',
                    'pendaftarans.is_final',
                    'pendaftarans.status_cadangan',
                    'pendaftarans.status_aktif',
                    'pendaftarans.status_diskualifikasi'
                );

            $pendaftarans = $this->applyPendaftaranFilters($request, $pendaftarans)
                ->get()
                ->transform(function ($pendaftaran) {
                    $nilaiAkademik = DB::table('nilai_akademik_pendaftars')
                        ->where('pendaftaran_id', $pendaftaran->id)
                        ->where('status', 'Accept')
                        ->avg('nilai') ?? 0;

                    $prestasiList = DB::table('prestasi_pendaftars')
                        ->where('pendaftaran_id', $pendaftaran->id)
                        ->get()
                        ->map(function ($prestasi) {
                            return [
                                'jumlah' => $prestasi->jumlah_prestasi,
                                'poin_per_prestasi' => $prestasi->point_prestasi ?? 0,
                            ];
                        })
                        ->toArray();

                    $nilaiAkhir = \App\Helpers\PrestasiHelper::hitungNilaiAkhir($nilaiAkademik, $prestasiList);
                    $pendaftaran->total_nilai = round($nilaiAkhir, 2);
                    return $pendaftaran;
                })
                ->sortByDesc('total_nilai') // ✅ Tambahkan ini untuk urutkan dari nilai tertinggi
                ->values(); // reset index agar urutan tetap rapih

            return DataTables::of($pendaftarans)
                ->addIndexColumn()
                ->addColumn('total_nilai', function ($row) {
                    return $row->total_nilai;
                })
                ->make(true);
        }
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pengumuman.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
