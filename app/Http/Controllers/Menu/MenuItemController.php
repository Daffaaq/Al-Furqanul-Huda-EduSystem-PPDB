<?php

namespace App\Http\Controllers\Menu;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMenuItemRequest;
use App\Http\Requests\UpdateMenuItemRequest;
use Illuminate\Http\Request;
use App\Models\MenuGroup;
use App\Models\MenuItem;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Route;

class MenuItemController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:menu-item.index')->only('index', 'list');
        $this->middleware('permission:menu-item.create')->only('create', 'store');
        $this->middleware('permission:menu-item.edit')->only('edit', 'update');
        $this->middleware('permission:menu-item.destroy')->only('destroy');
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            $menuItems = DB::table('menu_items')
                ->select('menu_items.id', 'menu_items.name', 'menu_items.route', 'menu_items.permission_name', 'menu_groups.name as menu_group_name')
                ->join('menu_groups', 'menu_items.menu_group_id', '=', 'menu_groups.id');

            return DataTables::of($menuItems)
                ->addIndexColumn()
                ->make(true);
        }
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('menu.menu-item.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $routeCollection = Route::getRoutes();
        $menuGroups = MenuGroup::all();
        return view('menu.menu-item.create', compact('routeCollection', 'menuGroups'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMenuItemRequest $request)
    {
        MenuItem::create($request->validated());
        return redirect()->route('menu-item.index')->with('success', 'Data berhasil ditambahkan');
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
    public function edit(MenuItem $menuItem)
    {
        return view('menu.menu-item.edit', compact('menuItem'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMenuItemRequest $request, MenuItem $menuItem)
    {
        $menuItem->update($request->validated());
        return redirect()->route('menu-item.index')->with('success', 'Data berhasil diubah');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MenuItem $menuItem)
    {
        $menuItem->delete();
        return response()->json([
            'success' => true,
            'message' => 'User Deleted Successfully'
        ]);
    }
}
