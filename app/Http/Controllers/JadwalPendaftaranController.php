<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreJadwalPendaftaranRequest;
use App\Http\Requests\UpdateJadwalPendaftaranRequest;
use App\Models\jadwalPendaftaran;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use App\Traits\FilterByPeriode;
use Illuminate\Http\Request;

class JadwalPendaftaranController extends Controller
{
    use FilterByPeriode;
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:jadwal-pendaftaran.index')->only('index', 'list');
        $this->middleware('permission:jadwal-pendaftaran.create')->only('create', 'store');
        $this->middleware('permission:jadwal-pendaftaran.edit')->only('edit', 'update');
        $this->middleware('permission:jadwal-pendaftaran.destroy')->only('destroy');
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            $jadwalPendaftaran = DB::table('jadwal_pendaftarans')
                ->leftJoin('periodes', 'jadwal_pendaftarans.periode_id', '=', 'periodes.id')
                ->select('jadwal_pendaftarans.id', 'jadwal_pendaftarans.nama_jadwal_pendaftaran', 'jadwal_pendaftarans.tanggal_mulai_jadwal_pendaftaran', 'jadwal_pendaftarans.tanggal_selesai_jadwal_pendaftaran', 'periodes.nama_periode', 'jadwal_pendaftarans.status_jadwal_pendaftaran');

            // Gunakan trait untuk filter
            $jadwalPendaftaran = $this->applyPeriodeFilter($request, $jadwalPendaftaran);
            
            return DataTables::of($jadwalPendaftaran)
                ->addIndexColumn()
                ->make(true);
        }
        return response()->json(['message' => 'Invalid request'], 400);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('jadwal-pendaftaran.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $periode = DB::table('periodes')->select('id', 'nama_periode', 'status_periode')->get();
        return view('jadwal-pendaftaran.create', compact('periode'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreJadwalPendaftaranRequest $request)
    {
        jadwalPendaftaran::create([
            'periode_id' => $request->periode_id,
            'nama_jadwal_pendaftaran' => $request->nama_jadwal_pendaftaran,
            'tanggal_mulai_jadwal_pendaftaran' => $request->tanggal_mulai_jadwal_pendaftaran,
            'tanggal_mulai_verifikasi' => $request->tanggal_mulai_verifikasi,
            'tanggal_selesai_verifikasi' => $request->tanggal_selesai_verifikasi,
            'tanggal_selesai_jadwal_pendaftaran' => $request->tanggal_selesai_jadwal_pendaftaran,
            'gelombang_pendaftaran' => $request->gelombang_pendaftaran,
            'deadline_biodata_calon_siswa' => $request->deadline_biodata_calon_siswa,
            'deadline_upload_pendaftaran' => $request->deadline_upload_pendaftaran,
            'pengumuman_hasil_seleksi' => $request->pengumuman_hasil_seleksi,
            'kuota_akun' => $request->kuota_akun,
            'kuota_pendaftaran' => $request->kuota_pendaftaran,
            'kuota_penerimaan' => $request->kuota_penerimaan,
            'status_jadwal_pendaftaran' => $request->status_jadwal_pendaftaran
        ]);

        return redirect()->route('jadwal-pendaftaran.index')->with('success', 'Jadwal Pendaftaran Berhasil Ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(jadwalPendaftaran $jadwalPendaftaran)
    {
        return view('jadwal-pendaftaran.show', compact('jadwalPendaftaran'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(jadwalPendaftaran $jadwalPendaftaran)
    {
        $periode = DB::table('periodes')->select('id', 'nama_periode', 'status_periode')->get();
        return view('jadwal-pendaftaran.edit', compact('jadwalPendaftaran', 'periode'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateJadwalPendaftaranRequest $request, jadwalPendaftaran $jadwalPendaftaran)
    {
        $jadwalPendaftaran->update([
            'periode_id' => $request->periode_id,
            'nama_jadwal_pendaftaran' => $request->nama_jadwal_pendaftaran,
            'tanggal_mulai_jadwal_pendaftaran' => $request->tanggal_mulai_jadwal_pendaftaran,
            'tanggal_mulai_verifikasi' => $request->tanggal_mulai_verifikasi,
            'tanggal_selesai_verifikasi' => $request->tanggal_selesai_verifikasi,
            'tanggal_selesai_jadwal_pendaftaran' => $request->tanggal_selesai_jadwal_pendaftaran,
            'gelombang_pendaftaran' => $request->gelombang_pendaftaran,
            'deadline_biodata_calon_siswa' => $request->deadline_biodata_calon_siswa,
            'deadline_upload_pendaftaran' => $request->deadline_upload_pendaftaran,
            'pengumuman_hasil_seleksi' => $request->pengumuman_hasil_seleksi,
            'kuota_akun' => $request->kuota_akun,
            'kuota_pendaftaran' => $request->kuota_pendaftaran,
            'kuota_penerimaan' => $request->kuota_penerimaan,
            'status_jadwal_pendaftaran' => $request->status_jadwal_pendaftaran
        ]);

        return redirect()->route('jadwal-pendaftaran.index')->with('success', 'Jadwal Pendaftaran Berhasil Diubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(jadwalPendaftaran $jadwalPendaftaran)
    {
        // mulai transaksi
        DB::beginTransaction();

        try {
            $jadwalPendaftaran->delete();
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Mata Pelajaran Seleksi Deleted Successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
