<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class UserRoleController extends Controller
{
    public function index(): View
    {
        $users = User::with('role')->orderBy('id')->get();
        $roles = Role::with('permissions')->orderBy('id')->get();
        $permissions = Permission::orderBy('module')->get()->groupBy('module');

        return view('admin.users.index', compact('users', 'roles', 'permissions'));
    }

    public function storeUser(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:30'],
            'role_id' => ['required', 'exists:roles,id'],
            'password' => ['required', Password::min(8)],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'role_id' => $validated['role_id'],
            'password' => Hash::make($validated['password']),
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        AuditLog::record('user.created', "Created user {$user->email}", $user);

        return back()->with('success', 'User created successfully.');
    }

    public function toggleUser(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return back()->withErrors(['user' => 'You cannot deactivate your own account.']);
        }

        $user->update(['is_active' => ! $user->is_active]);
        AuditLog::record('user.toggled', "Toggled active status for {$user->email}", $user);

        return back()->with('success', 'User status updated.');
    }

    public function updateRolePermissions(Request $request, Role $role): RedirectResponse
    {
        if ($role->slug === 'super-admin') {
            return back()->withErrors(['role' => 'Super Administrator always retains all permissions.']);
        }

        $permissionIds = $request->input('permissions', []);
        $role->permissions()->sync($permissionIds);
        AuditLog::record('role.permissions_updated', "Updated permissions for role {$role->name}", $role);

        return back()->with('success', "Permissions updated for {$role->name}.");
    }
}
