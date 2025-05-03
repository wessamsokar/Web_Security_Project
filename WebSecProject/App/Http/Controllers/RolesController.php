<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesController extends Controller
{
    public function list()
    {
        if (!auth()->user()->hasPermissionTo('manage_roles')) {
            abort(403);
        }

        $roles = Role::with('permissions')->get();
        return view('roles.list', compact('roles'));
    }

    public function edit(Role $role = null)
    {
        if (!auth()->user()->hasPermissionTo('manage_roles')) {
            abort(403);
        }

        $permissions = Permission::all();
        $rolePermissions = $role ? $role->permissions->pluck('id')->toArray() : [];
        
        return view('roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    public function save(Request $request, Role $role = null)
    {
        if (!auth()->user()->hasPermissionTo('manage_roles')) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'permissions' => ['required', 'array'],
            'permissions.*' => ['exists:permissions,id']
        ]);

        if (!$role) {
            $role = Role::create(['name' => $validated['name']]);
        } else {
            $role->update(['name' => $validated['name']]);
        }

        // Fix: Get Permission models by their IDs before syncing
        $permissionModels = Permission::whereIn('id', $validated['permissions'])->get();
        $role->syncPermissions($permissionModels);

        return redirect()->route('roles_list')->with('success', 'Role saved successfully');
    }

    public function delete(Role $role)
    {
        if (!auth()->user()->hasPermissionTo('manage_roles')) {
            abort(403);
        }

        $role->delete();
        return redirect()->route('roles_list')->with('success', 'Role deleted successfully');
    }
}