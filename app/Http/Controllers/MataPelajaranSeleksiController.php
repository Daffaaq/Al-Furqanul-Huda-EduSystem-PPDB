<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMataPelajaranSeleksiRequest;
use App\Http\Requests\UpdateMataPelajaranSeleksiRequest;
use App\Models\MataPelajaranSeleksi;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use App\Traits\FilterByPeriode;
use Illuminate\Http\Request;

class MataPelajaranSeleksiController extends Controller
{
    use FilterByPeriode;
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:mata-pelajaran-seleksi.index')->only('index', 'list');
        $this->middleware('permission:mata-pelajaran-seleksi.create')->only('create', 'store');
        $this->middleware('permission:mata-pelajaran-seleksi.edit')->only('edit', 'update');
        $this->middleware('permission:mata-pelajaran-seleksi.destroy')->only('destroy');
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            // Query tanpa eksekusi get()
            $query = DB::table('mata_pelajaran_seleksis')
                ->leftJoin('periodes', 'mata_pelajaran_seleksis.periode_id', '=', 'periodes.id')
                ->select('mata_pelajaran_seleksis.id', 'mata_pelajaran_seleksis.nama_mata_pelajaran_seleksi', 'periodes.nama_periode');

            // Gunakan trait untuk filter
            $query = $this->applyPeriodeFilter($request, $query);

            // Menggunakan DataTables untuk memproses data dan mengembalikan response
            return DataTables::of($query)
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
        return view('mata-pelajaran-seleksi.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $periode = DB::table('periodes')->select('id', 'nama_periode', 'status_periode')->get();
        return view('mata-pelajaran-seleksi.create', compact('periode'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMataPelajaranSeleksiRequest $request)
    {
        // Loop through each mata pelajaran and create a new record
        foreach ($request->nama_mata_pelajaran_seleksi as $key => $namaMataPelajaran) {
            MataPelajaranSeleksi::create([
                'nama_mata_pelajaran_seleksi' => $namaMataPelajaran,
                'periode_id' => $request->periode_id[$key], // Use the corresponding periode_id for each mata pelajaran
            ]);
        }

        return redirect()->route('mata-pelajaran-seleksi.index')->with('success', 'Data Mata Pelajaran Seleksi Berhasil Ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(MataPelajaranSeleksi $mataPelajaranSeleksi)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MataPelajaranSeleksi $mataPelajaranSeleksi)
    {
        $periode = DB::table('periodes')->select('id', 'nama_periode', 'status_periode')->get();
        return view('mata-pelajaran-seleksi.edit')
            ->with('mataPelajaranSeleksi', $mataPelajaranSeleksi)
            ->with('periode', $periode);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMataPelajaranSeleksiRequest $request, MataPelajaranSeleksi $mataPelajaranSeleksi)
    {
        $mataPelajaranSeleksi->update([
            'nama_mata_pelajaran_seleksi' => $request['nama_mata_pelajaran_seleksi'],
            'periode_id' => $request['periode_id']
        ]);

        return redirect()->route('mata-pelajaran-seleksi.index')->with('success', 'Data Mata Pelajaran Seleksi Berhasil Diubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MataPelajaranSeleksi $mataPelajaranSeleksi)
    {
        // mulai transaksi
        DB::beginTransaction();

        try {
            $mataPelajaranSeleksi->delete();
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
