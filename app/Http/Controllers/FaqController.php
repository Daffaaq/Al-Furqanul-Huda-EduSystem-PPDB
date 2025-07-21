<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFaqRequest;
use App\Http\Requests\UpdateFaqRequest;
use App\Models\Faq;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use App\Traits\FilterByPeriode;

class FaqController extends Controller
{
    use FilterByPeriode;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:faq.index')->only('index', 'list');
        $this->middleware('permission:faq.create')->only('create', 'store');
        $this->middleware('permission:faq.edit')->only('edit', 'update');
        $this->middleware('permission:faq.destroy')->only('destroy');
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            $faq = DB::table('faqs')
                ->leftJoin('periodes', 'faqs.periode_id', '=', 'periodes.id')
                ->select('faqs.id', 'faqs.question', 'periodes.nama_periode');

            // Gunakan trait untuk filter
            $faq = $this->applyPeriodeFilter($request, $faq);

            return DataTables::of($faq)
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
        return view('faq.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $periodelist = DB::table('periodes')->select('id', 'nama_periode', 'status_periode')->get();
        return view('faq.create', compact('periodelist'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFaqRequest $request)
    {
        $faq = Faq::create($request->all());
        return redirect()->route('faq.index')->with('success', 'Faq created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Faq $faq)
    {
        $faq->load('periode');
        return view('faq.show', compact('faq'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Faq $faq)
    {
        $periodelist = DB::table('periodes')->select('id', 'nama_periode', 'status_periode')->get();
        return view('faq.edit', compact('faq', 'periodelist'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFaqRequest $request, Faq $faq)
    {
        $faq->update($request->all());
        return redirect()->route('faq.index')->with('success', 'Faq updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Faq $faq)
    {
        // mulai transaksi
        DB::beginTransaction();
        try {
            $faq->delete();
            DB::commit();
            return response()->json(['success' => true, 'message' => 'Faq Berhasil Dihapus']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Faq Gagal Dihapus']);
        }
    }
}
