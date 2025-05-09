<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Routing\Controller;
use App\Models\User;

class RoleController extends Controller
{

    /**
     * Display a listing of the roles.
     */
    public function index(Request $request)
    {
        $query = Role::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        if ($request->filled('permission')) {
            $query->whereHas('permissions', function ($q) use ($request) {
                $q->where('id', $request->permission);
            });
        }

        $roles = Role::with(['permissions', 'users'])
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->when($request->permission, function ($query, $permission) {
                $query->whereHas('permissions', function ($q) use ($permission) {
                    $q->where('id', $permission);
                });
            })
            ->paginate(5);  // Changed from get() to paginate()

        $permissions = Permission::all();

        return view('roles.index', compact('roles', 'permissions'));
    }

    /**
     * Show the form for creating a new role.
     */
    public function create()
    {
        $permissions = Permission::all();
        return view('roles.create', compact('permissions'));
    }

    /**
     * Store a newly created role in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles',
            'permissions' => 'required|array',
        ]);

        $role = Role::create(['name' => $validated['name']]);

        // Get the actual Permission models and sync them
        $permissions = Permission::whereIn('id', $request->permissions)->get();
        $role->syncPermissions($permissions);

        return redirect()->route('roles.index')
            ->with('success', 'Role created successfully.');
    }

    /**
     * Display the specified role.
     */
    public function show(Role $role, Request $request)
    {
        $search = $request->get('search');

        $users = $role->users()
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%");
                });
            })
            ->paginate(10)
            ->withQueryString();

        return view('roles.show', compact('role', 'users', 'search'));
    }

    /**
     * Show the form for editing the specified role.
     */
    public function edit(Role $role, Request $request)
    {
        $permissions = Permission::all();
        $search = $request->get('search');

        $users = $role->users()
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%");
                });
            })
            ->paginate(10)
            ->withQueryString();

        return view('roles.edit', compact('role', 'permissions', 'users', 'search'));
    }

    /**
     * Update the specified role in storage.
     */
    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'permissions' => 'required|array',
        ]);

        // Get the actual Permission models and sync them
        $permissions = Permission::whereIn('id', $request->permissions)->get();
        $role->syncPermissions($permissions);

        return redirect()->route('roles.index')
            ->with('success', 'Role permissions updated successfully.');
    }

    /**
     * Remove the specified role from storage.
     */
    public function destroy(Role $role)
    {
        if ($role->name === 'Super Admin') {
            return redirect()->back()->with('error', 'You cannot delete the Super Admin role.');
        }

        // Get all users with this role
        $users = $role->users;

        // Get or create the Customer role
        $customerRole = Role::firstOrCreate(['name' => 'Customer']);

        // For each user, remove the current role and assign Customer role
        foreach ($users as $user) {
            $user->removeRole($role);
            if (!$user->hasAnyRole()) {
                $user->assignRole($customerRole);
            }
        }

        $role->delete();

        return redirect()->route('roles.index')
            ->with('success', 'Role deleted successfully. All affected users have been assigned the Customer role.');
    }

    /**
     * Remove a user from a specific role
     */
    public function removeUser(Request $request, Role $role)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        $user = User::findOrFail($validated['user_id']);
        $user->removeRole($role);

        // If user has no roles, assign Customer role
        if (!$user->hasAnyRole()) {
            $customerRole = Role::firstOrCreate(['name' => 'Customer']);
            $user->assignRole($customerRole);
        }

        return redirect()->back()
            ->with('success', 'User removed from role successfully.');
    }
}
