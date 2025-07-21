<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBobotPendaftaranRequest;
use App\Http\Requests\UpdateBobotPendaftaranRequest;
use App\Models\BobotPendaftaran;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use App\Traits\FilterByPeriode;
use Illuminate\Http\Request;

class BobotPendaftaranController extends Controller
{
    use FilterByPeriode;
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:bobot-pendaftaran.index')->only('index', 'list');
        $this->middleware('permission:bobot-pendaftaran.create')->only('create', 'store');
        $this->middleware('permission:bobot-pendaftaran.edit')->only('edit', 'update');
        $this->middleware('permission:bobot-pendaftaran.destroy')->only('destroy');
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            $bobotPendaftaran = DB::table('bobot_pendaftarans')
                ->leftJoin('periodes', 'bobot_pendaftarans.periode_id', '=', 'periodes.id')
                ->select('bobot_pendaftarans.id', 'bobot_pendaftarans.bobot_akademik', 'bobot_pendaftarans.bobot_non_akademik', 'periodes.nama_periode');

            // Gunakan trait untuk filter
            $bobotPendaftaran = $this->applyPeriodeFilter($request, $bobotPendaftaran);

            return DataTables::of($bobotPendaftaran)
                ->addIndexColumn()
                ->make(true);
        }
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('bobot-pendaftaran.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $periodelist = DB::table('periodes')->select('id', 'nama_periode', 'status_periode')->get();
        return view('bobot-pendaftaran.create', compact('periodelist'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Ambil nilai bobot dari request
        $bobotAkademik = $request->bobot_akademik;
        $bobotNonAkademik = $request->bobot_non_akademik;

        // Pastikan nilai bobot dalam format desimal (misalnya, 60 menjadi 0.6, 40 menjadi 0.4)
        if ($bobotAkademik >= 1) {
            $bobotAkademik = $bobotAkademik / 100;
        }

        if ($bobotNonAkademik >= 1) {
            $bobotNonAkademik = $bobotNonAkademik / 100;
        }

        // Hitung total bobot
        $totalBobot = $bobotAkademik + $bobotNonAkademik;

        // Jika total bobot tidak sama dengan 1, sesuaikan bobot non-akademik
        if ($totalBobot !== 1) {
            // Sesuaikan bobot non-akademik untuk mencapai total 1
            $bobotNonAkademik = 1 - $bobotAkademik;
        }

        // Simpan data yang telah disesuaikan ke database
        BobotPendaftaran::create([
            'periode_id' => $request->periode_id,
            'bobot_akademik' => $bobotAkademik,
            'bobot_non_akademik' => $bobotNonAkademik,
        ]);

        return response()->json(['success' => true, 'message' => 'Bobot Pendaftaran Berhasil Ditambahkan']);
    }

    /**
     * Display the specified resource.
     */
    public function show(BobotPendaftaran $bobotPendaftaran)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BobotPendaftaran $bobotPendaftaran)
    {
        $periodelist = DB::table('periodes')->select('id', 'nama_periode', 'status_periode')->get();
        return view('bobot-pendaftaran.edit', compact('bobotPendaftaran', 'periodelist'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBobotPendaftaranRequest $request, BobotPendaftaran $bobotPendaftaran)
    {
        // Ambil nilai bobot dari request
        $bobotAkademik = $request->bobot_akademik;
        $bobotNonAkademik = $request->bobot_non_akademik;

        // Pastikan nilai bobot dalam format desimal (misalnya, 60 menjadi 0.6, 40 menjadi 0.4)
        if ($bobotAkademik >= 1) {
            $bobotAkademik = $bobotAkademik / 100;
        }

        if ($bobotNonAkademik >= 1) {
            $bobotNonAkademik = $bobotNonAkademik / 100;
        }

        // Hitung total bobot
        $totalBobot = $bobotAkademik + $bobotNonAkademik;

        // Jika total bobot tidak sama dengan 1, sesuaikan bobot non-akademik
        if ($totalBobot !== 1) {
            // Sesuaikan bobot non-akademik untuk mencapai total 1
            $bobotNonAkademik = 1 - $bobotAkademik;
        }

        // Update data bobot pendaftaran
        $bobotPendaftaran->update([
            'periode_id' => $request->periode_id,
            'bobot_akademik' => $bobotAkademik,
            'bobot_non_akademik' => $bobotNonAkademik,
        ]);

        return response()->json(['success' => true, 'message' => 'Bobot Pendaftaran Berhasil Diperbarui']);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BobotPendaftaran $bobotPendaftaran)
    {
        // mulai transaksi
        DB::beginTransaction();

        try {
            $bobotPendaftaran->delete();
            DB::commit();
            return response()->json(['success' => true, 'message' => 'Bobot Pendaftaran Berhasil Dihapus']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Bobot Pendaftaran Gagal Dihapus']);
        }
    }
}
