<?php

namespace App\Http\Controllers\Super;

use App\Helpers\ActivityLogger;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Inertia\Inertia;

class RoleManagerController extends Controller
{
    public function index()
    {
        $roles = Role::withCount('users')->with('permissions')->get();
        return Inertia::render('Super/Roles/Index', ['roles' => $roles]);
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|unique:roles,name']);

        $role = Role::create(['name' => $request->name]);

        ActivityLogger::log("Created role: {$role->name}", 'security');

        return back()->with('success', 'Role berhasil dibuat.');
    }

    public function update(Request $request, Role $role)
    {
        // Mencegah edit super-admin demi keamanan
        if ($role->name === 'super-admin') {
            return back()->with('error', 'Role Super Admin tidak dapat diubah.');
        }

        $request->validate(['permissions' => 'array']);

        // Sync permissions (array nama permission)
        $role->syncPermissions($request->permissions);

        ActivityLogger::log("Updated permissions for role: {$role->name}", 'security');

        return back()->with('success', 'Hak akses role berhasil diperbarui.');
    }

    public function destroy(Role $role)
    {
        if ($role->name === 'super-admin') {
            return back()->with('error', 'Role Super Admin tidak dapat dihapus.');
        }

        $role->delete();
        ActivityLogger::log("Deleted role: {$role->name}", 'security', null, [], 'danger');

        return back()->with('success', 'Role berhasil dihapus.');
    }
}
