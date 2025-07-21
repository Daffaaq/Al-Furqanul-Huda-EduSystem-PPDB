<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePeriodeRequest;
use App\Http\Requests\UpdatePeriodeRequest;
use App\Models\Periode;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class PeriodeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:periode.index')->only('index', 'list');
        $this->middleware('permission:periode.create')->only('create', 'store');
        $this->middleware('permission:periode.edit')->only('edit', 'update');
        $this->middleware('permission:periode.destroy')->only('destroy');
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            $periode = DB::table('periodes')
                ->select('id', 'nama_periode', 'status_periode');
            return DataTables::of($periode)
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
        return view('periode.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('periode.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePeriodeRequest $request)
    {
        if ($request->status_periode == 1) {
            // Cek apakah sudah ada periode aktif di database
            $periodeactive = DB::table('periodes')
                ->where('status_periode', '1')
                ->first();

            // Jika ada, tolak permintaan
            if ($periodeactive) {
                return redirect()->route('periode.index')->with('error', 'Sudah ada periode yang aktif!');
            }
        }
        Periode::create([
            'nama_periode' => $request->nama_periode,
            'status_periode' => $request->status_periode
        ]);

        return redirect()->route('periode.index')->with('success', 'Periode Created Successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Periode $periode)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Periode $periode)
    {
        return view('periode.edit')
            ->with('periode', $periode);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePeriodeRequest $request, Periode $periode)
    {
        $periodeactive = DB::table('periodes')
            ->where('status_periode', '1')
            ->where('id', '!=', $periode->id)  // exclude current periode by id
            ->first();

        if ($periodeactive && $request->status_periode == 1) {
            return redirect()->route('periode.index')->with('error', 'Sudah ada periode yang aktif!');
        }
        $periode->update([
            'nama_periode' => $request->nama_periode,
            'status_periode' => $request->status_periode
        ]);

        return redirect()->route('periode.index')->with('success', 'Periode Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Periode $periode)
    {
        // mulai transaksi
        DB::beginTransaction();

        try {
            $periode->delete();
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Periode Deleted Successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete the Periode. Please try again later.'
            ]);
        }
    }

    public function checkActiveExcept(Periode $periode)
    {
        $otherActive = Periode::where('status_periode', 1)
            ->where('id', '!=', $periode->id)
            ->exists();

        return response()->json([
            'has_other_active' => $otherActive
        ]);
    }


    public function updateStatus(Periode $periode)
    {
        DB::beginTransaction();
        try {
            if ($periode->status_periode == 0) {
                // Matikan periode lain yg aktif
                Periode::where('status_periode', 1)->update(['status_periode' => 0]);
                // Aktifkan periode ini
                $periode->status_periode = 1;
            } else {
                // Jika sudah aktif, nonaktifkan saja
                $periode->status_periode = 0;
            }
            $periode->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Status Periode Updated Successfully',
                'new_status' => $periode->status_periode,
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
