@extends('layouts.admin')

@section('title', 'Users & Roles')
@section('page_title', 'User & Role-Based Access Control')

@section('content')
<div class="row g-4 mb-4">
    <div class="col-lg-4">
        <div class="card kpi-card p-4">
            <h6 class="fw-bold mb-3">Create Admin / Sales User</h6>
            <form method="POST" action="{{ route('admin.users.store') }}" class="d-flex flex-column gap-2">
                @csrf
                <input type="text" name="name" class="form-control form-control-sm" placeholder="Full Name *" required>
                <input type="email" name="email" class="form-control form-control-sm" placeholder="Email Address *" required>
                <input type="text" name="phone" class="form-control form-control-sm" placeholder="Phone">
                <select name="role_id" class="form-select form-select-sm" required>
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                    @endforeach
                </select>
                <input type="password" name="password" class="form-control form-control-sm" placeholder="Password (min 8 chars) *" required>
                <button type="submit" class="btn btn-primary btn-sm mt-2">Create User</button>
            </form>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card kpi-card p-4">
            <h6 class="fw-bold mb-3">System Users</h6>
            <div class="table-responsive">
                <table class="table table-sm align-middle mb-0">
                    <thead><tr><th>Name</th><th>Email</th><th>Role</th><th>Status</th><th></th></tr></thead>
                    <tbody>
                    @foreach($users as $u)
                        <tr>
                            <td class="fw-semibold">{{ $u->name }}</td>
                            <td class="small">{{ $u->email }}</td>
                            <td><span class="badge bg-info text-dark">{{ $u->role?->name }}</span></td>
                            <td><span class="badge {{ $u->is_active ? 'bg-success' : 'bg-danger' }}">{{ $u->is_active ? 'Active' : 'Inactive' }}</span></td>
                            <td class="text-end">
                                @if($u->id !== auth()->id())
                                    <form method="POST" action="{{ route('admin.users.toggle', $u) }}">@csrf
                                        <button class="btn btn-sm btn-outline-secondary">{{ $u->is_active ? 'Deactivate' : 'Activate' }}</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="card kpi-card p-4">
    <h6 class="fw-bold mb-3">Role Permissions Matrix</h6>
    <div class="row g-4">
        @foreach($roles as $role)
            <div class="col-md-6">
                <form method="POST" action="{{ route('admin.roles.permissions', $role) }}" class="border rounded p-3 h-100">
                    @csrf
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <strong>{{ $role->name }}</strong>
                        @if($role->slug !== 'super-admin')
                            <button type="submit" class="btn btn-sm btn-outline-primary">Save</button>
                        @else
                            <span class="badge bg-warning text-dark">Full Access</span>
                        @endif
                    </div>
                    <div class="row g-1 small">
                        @php $rolePermIds = $role->permissions->pluck('id')->toArray(); @endphp
                        @foreach($permissions as $module => $perms)
                            @foreach($perms as $perm)
                                <div class="col-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $perm->id }}"
                                               id="r{{ $role->id }}p{{ $perm->id }}"
                                               @checked($role->slug === 'super-admin' || in_array($perm->id, $rolePermIds))
                                               @disabled($role->slug === 'super-admin')>
                                        <label class="form-check-label" for="r{{ $role->id }}p{{ $perm->id }}">{{ $perm->name }}</label>
                                    </div>
                                </div>
                            @endforeach
                        @endforeach
                    </div>
                </form>
            </div>
        @endforeach
    </div>
</div>
@endsection
