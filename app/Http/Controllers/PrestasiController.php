<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddPrestasiRequest;
use App\Http\Requests\StoreKategoriPrestasiRequest;
use App\Http\Requests\StorePrestasiRequest;
use App\Models\KategoriPrestasi;
use App\Models\Prestasi;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use App\Traits\FilterByPeriode;
use Illuminate\Http\Request;

class PrestasiController extends Controller
{
    use FilterByPeriode;
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:kategori-prestasi.index')->only('index', 'list');
        $this->middleware('permission:kategori-prestasi.create')->only('create', 'store');
        $this->middleware('permission:kategori-prestasi.edit')->only('edit', 'update');
        $this->middleware('permission:kategori-prestasi.destroy')->only('destroy');
    }


    //                              -- Prestasi --                  //
    public function listPrestasi(Request $request)
    {
        if ($request->ajax()) {
            $query = DB::table('prestasis')
                ->leftJoin('kategori_prestasis', 'prestasis.kategori_prestasi_id', '=', 'kategori_prestasis.id')
                ->leftJoin('periodes', 'kategori_prestasis.periode_id', '=', 'periodes.id')
                ->select(
                    'prestasis.id',
                    'prestasis.nama_prestasi',
                    'kategori_prestasis.nama_kategori_prestasi',
                    'prestasis.point_prestasi',
                    'periodes.nama_periode',
                    'kategori_prestasis.id as kategori_prestasi_id'
                );

            // Gunakan trait untuk filter
            $query = $this->applyPeriodeFilter($request, $query);

            return DataTables::of($query)
                ->addIndexColumn()
                ->make(true);
        }
        // return response json
        return response()->json(['message' => 'Invalid request'], 400);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('prestasi.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $periode = DB::table('periodes')->select('id', 'nama_periode', 'status_periode')->get();
        return view('prestasi.create', compact('periode'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePrestasiRequest $request)
    {
        // Buat kategori prestasi dulu
        $kategoriPrestasi = KategoriPrestasi::create([
            'nama_kategori_prestasi' => $request->nama_kategori_prestasi,
            'periode_id' => $request->periode_id
        ]);

        // Looping prestasi
        foreach ($request->nama_prestasi as $key => $nama) {
            Prestasi::create([
                'kategori_prestasi_id' => $kategoriPrestasi->id,
                'nama_prestasi' => $nama,
                'point_prestasi' => $request->point_prestasi[$key]
            ]);
        }

        return redirect()->route('kategori-prestasi.index')->with('success', 'Prestasi berhasil disimpan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Prestasi $prestasi)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $kategori = KategoriPrestasi::with('prestasi')->findOrFail($id);
        $periodes = DB::table('periodes')->select('id', 'nama_periode', 'status_periode')->get();

        return view('prestasi.edit', compact('kategori', 'periodes'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(StorePrestasiRequest $request, $id)
    {
        $kategori = KategoriPrestasi::findOrFail($id);

        // Update kategori
        $kategori->update([
            'nama_kategori_prestasi' => $request->nama_kategori_prestasi,
            'periode_id' => $request->periode_id
        ]);

        // Update setiap prestasi
        foreach ($request->prestasi_id as $index => $prestasiId) {
            Prestasi::where('id', $prestasiId)->update([
                'nama_prestasi' => $request->nama_prestasi[$index],
                'point_prestasi' => $request->point_prestasi[$index]
            ]);
        }

        return redirect()->route('kategori-prestasi.index')->with('success', 'Data berhasil diperbarui');
    }


    public function checkPrestasiCount($kategoriId)
    {
        $count = DB::table('prestasis')->where('kategori_prestasi_id', $kategoriId)->count();
        // dd($count);
        return response()->json(['count' => $count]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // mulai transaksi
        DB::beginTransaction();

        try {
            $prestasi = Prestasi::findOrFail($id);
            $prestasi->delete();
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Prestasi Deleted Successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    //                              -- Kategori Prestasi --                  //

    public function listKategoriPrestasi(Request $request)
    {
        if ($request->ajax()) {
            $query = DB::table('kategori_prestasis')
                ->leftJoin('periodes', 'kategori_prestasis.periode_id', '=', 'periodes.id')
                ->leftJoin('prestasis', 'kategori_prestasis.id', '=', 'prestasis.kategori_prestasi_id')
                ->select(
                    'kategori_prestasis.id',
                    'kategori_prestasis.nama_kategori_prestasi',
                    'periodes.nama_periode',
                    DB::raw('COUNT(prestasis.id) as jumlah_prestasi')
                )
                ->groupBy('kategori_prestasis.id', 'kategori_prestasis.nama_kategori_prestasi', 'periodes.nama_periode');

            // Gunakan trait untuk filter
            $query = $this->applyPeriodeFilter($request, $query);

            return DataTables::of($query)
                ->addIndexColumn()
                ->filterColumn('jumlah_prestasi', function ($query, $keyword) {
                    // Konversi keyword ke integer jika mau exact
                    $query->havingRaw("COUNT(prestasis.id) LIKE ?", ["%{$keyword}%"]);
                })
                ->make(true);
        }
        return response()->json(['message' => 'Invalid request'], 400);
    }


    public function editKategoriPrestasi($id)
    {
        $kategori = KategoriPrestasi::with('prestasi')->findOrFail($id);
        $periodes = DB::table('periodes')->select('id', 'nama_periode', 'status_periode')->get();
        return view('prestasi.kategori.edit', compact('kategori', 'periodes'));
    }

    public function updateKategoriPrestasi(StoreKategoriPrestasiRequest $request, $id)
    {
        $kategori = KategoriPrestasi::findOrFail($id);
        $kategori->update([
            'nama_kategori_prestasi' => $request->nama_kategori_prestasi,
            'periode_id' => $request->periode_id
        ]);
        return redirect()->route('kategori-prestasi.index')->with('success', 'Data berhasil diperbarui');
    }

    public function destroyKategoriPrestasi($id)
    {
        DB::beginTransaction();
        try {
            $kategori = KategoriPrestasi::findOrFail($id);
            $kategori->delete();
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Kategori Prestasi Deleted Successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    //                                -- Prestasi --                          //

    public function addPrestasi(Request $request, $id)
    {
        $kategori = KategoriPrestasi::with('prestasi')->findOrFail($id);
        return view('prestasi.prestasi.add-prestasi', compact('kategori'));
    }

    public function storePrestasi(AddPrestasiRequest $request, $id)
    {
        $kategori = KategoriPrestasi::findOrFail($id);

        foreach ($request->nama_prestasi as $key => $nama) {
            Prestasi::create([
                'kategori_prestasi_id' => $kategori->id,
                'nama_prestasi' => $nama,
                'point_prestasi' => $request->point_prestasi[$key]
            ]);
        }

        return redirect()->route('kategori-prestasi.index')->with('success', 'Prestasi berhasil disimpan.');
    }
}
