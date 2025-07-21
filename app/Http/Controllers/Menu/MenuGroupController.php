<?php

namespace App\Http\Controllers\Menu;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMenuGroupRequest;
use App\Http\Requests\UpdateMenuGroupRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Spatie\Permission\Models\Permission;
use App\Models\MenuGroup;

class MenuGroupController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:menu-group.index')->only('index', 'list');
        $this->middleware('permission:menu-group.create')->only('create', 'store');
        $this->middleware('permission:menu-group.edit')->only('edit', 'update');
        $this->middleware('permission:menu-group.destroy')->only('destroy');
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            $menuGroups = DB::table('menu_groups')->select('name', 'id', 'permission_name')->get();
            return DataTables::of($menuGroups)
                ->addIndexColumn()
                ->make(true);
        }
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('menu.menu-group.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $permissions = Permission::all();
        return view('menu.menu-group.create', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMenuGroupRequest $request)
    {
        MenuGroup::create($request->validated());
        return redirect()->route('menu-group.index')->with('success', 'Data berhasil ditambahkan');
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
    public function edit(MenuGroup $menuGroup)
    {
        //
        return view('menu.menu-group.edit', compact('menuGroup'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMenuGroupRequest $request, MenuGroup $menuGroup)
    {
        $menuGroup->update($request->validated());
        return redirect()->route('menu-group.index')->with('success', 'Data berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MenuGroup $menuGroup)
    {
        //
        $menuGroup->delete();
        return response()->json([
            'success' => true,
            'message' => 'User Deleted Successfully'
        ]);
    }
}
