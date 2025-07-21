<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreQuotesRequest;
use App\Http\Requests\UpdateQuotesRequest;
use App\Models\Quote;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use App\Traits\FilterByPeriode;

class QuoteController extends Controller
{
    use FilterByPeriode;

    // Untuk Login
    public function ajax(Request $request)
    {
        $quotes = Quote::whereHas('periode', function ($q) {
            $q->where('status_periode', 1);
        })
            ->get(['quotes as text', 'author']);

        return response()->json($quotes);
    }

    public function __construct()
    {
        $this->middleware('auth')->except('ajax');
        $this->middleware('permission:quote.index')->only('index', 'list');
        $this->middleware('permission:quote.create')->only('create', 'store');
        $this->middleware('permission:quote.edit')->only('edit', 'update');
        $this->middleware('permission:quote.destroy')->only('destroy');
    }


    public function list(Request $request)
    {
        if ($request->ajax()) {
            $quote = DB::table('quotes')
                ->leftJoin('periodes', 'quotes.periode_id', '=', 'periodes.id')
                ->select('quotes.id', 'quotes.author', 'periodes.nama_periode', 'quotes.quotes'); // <== ubah quotes.quotes ke quotes.quote

            // filter periode
            $quote = $this->applyPeriodeFilter($request, $quote, 'quotes');

            return DataTables::of($quote)
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
        return view('quote.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $periodelist = DB::table('periodes')->select('id', 'nama_periode', 'status_periode')->get();
        return view('quote.create', compact('periodelist'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreQuotesRequest $request)
    {
        Quote::create($request->validated());
        return redirect()->route('quote.index')->with('success', 'Quotes created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Quote $quote)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Quote $quote)
    {
        $periodelist = DB::table('periodes')->select('id', 'nama_periode', 'status_periode')->get();
        return view('quote.edit', compact('quote', 'periodelist'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateQuotesRequest $request, Quote $quote)
    {
        $quote->update($request->validated());
        return redirect()->route('quote.index')->with('success', 'Quotes updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Quote $quote)
    {
        // mulai transaksi
        DB::beginTransaction();
        try {
            $quote->delete();
            DB::commit();
            return response()->json(['success' => true, 'message' => 'Quotes deleted successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
