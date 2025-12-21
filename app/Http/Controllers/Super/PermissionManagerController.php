<?php

namespace App\Http\Controllers\Super;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Inertia\Inertia;

class PermissionManagerController extends Controller
{
    public function index()
    {
        $permissions = Permission::orderBy('name')->paginate(50);
        $roles = Role::select('id', 'name')->get();

        return Inertia::render('Super/Permissions/Index', [
            'permissions' => $permissions,
            'roles' => $roles
        ]);
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|unique:permissions,name']);

        Permission::create(['name' => $request->name, 'guard_name' => 'web']);

        return back()->with('success', 'Permission manual berhasil ditambahkan.');
    }
}
