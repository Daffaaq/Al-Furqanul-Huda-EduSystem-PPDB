<?php

namespace App\Http\Controllers\RoleAndPermission;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserToRoleRequest;
use App\Http\Requests\UpdateUserToRoleRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;


class AssignUserToRoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:assign.user.index')->only('index');
        $this->middleware('permission:assign.user.create')->only('create', 'store');
        $this->middleware('permission:assign.user.edit')->only('edit', 'update');
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            $users = User::all(); // Mengambil semua user beserta role-nya
            $data = $users->map(function ($user) {
                return [
                    'DT_RowIndex' => $user->getKey(),
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->getRoleNames()->implode(', '), // Menggabungkan nama role dengan koma
                    'id' => $user->id,
                ];
            });

            return response()->json(['data' => $data]); // Mengembalikan data dalam bentuk JSON
        }
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('permissions.user.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();
        $users = User::all();
        return view('permissions.user.create', compact('roles', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserToRoleRequest $request)
    {
        // Menemukan user berdasarkan ID
        $user = User::findOrFail($request->user);

        // Menemukan role berdasarkan ID yang dipilih
        $userRole = Role::whereIn('id', $request->roles)->get();

        // Mengassign role ke user
        $user->syncRoles($userRole);
        return redirect()->route('assign.user.index')->with('success', 'User Assigned To Role Successfully');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        $users = User::all();
        return view('permissions.user.edit', compact('user', 'roles', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserToRoleRequest $request, User $user)
    {
        $userRole = Role::whereIn('id', $request->roles)->get();
        $user->syncRoles($userRole);
        return redirect()->route('assign.user.index')->with('success', 'User Assigned To Role Successfully');
    }
}
