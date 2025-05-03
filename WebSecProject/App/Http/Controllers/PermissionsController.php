<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionsController extends Controller
{
    public function list()
    {
        // Check if user has permission to manage roles
        if (!auth()->user()->hasPermissionTo('manage_roles')) {
            abort(403);
        }

        $permissions = Permission::all();
        return view('permissions.list', compact('permissions'));
    }

    public function edit(Permission $permission = null)
    {
        if (!auth()->user()->hasPermissionTo('manage_roles')) {
            abort(403);
        }

        $permission = $permission ?? new Permission();
        return view('permissions.edit', compact('permission'));
    }

    public function save(Request $request, Permission $permission = null)
    {
        if (!auth()->user()->hasPermissionTo('manage_roles')) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'display_name' => ['nullable', 'string', 'max:255'],
        ]);

        if (!$permission) {
            // Create new permission
            $permission = Permission::create([
                'name' => $validated['name'],
                'guard_name' => 'web',
            ]);
        } else {
            // Update existing permission
            $permission->update([
                'name' => $validated['name'],
            ]);
        }

        // Update display_name if provided
        if (isset($validated['display_name'])) {
            $permission->display_name = $validated['display_name'];
            $permission->save();
        }

        return redirect()->route('permissions_list')->with('success', 'Permission saved successfully');
    }

    public function delete(Permission $permission)
    {
        if (!auth()->user()->hasPermissionTo('manage_roles')) {
            abort(403);
        }

        $permission->delete();
        return redirect()->route('permissions_list')->with('success', 'Permission deleted successfully');
    }
}