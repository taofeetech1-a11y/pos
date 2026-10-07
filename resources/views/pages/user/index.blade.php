@extends("main_master")
@section("main")

<div class="container-xxl">

    <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
        <div class="flex-grow-1">
            <h4 class="fs-18 fw-semibold m-0">User Management</h4>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Summary Cards -->
    <div class="row mb-3">
        <div class="col-md-2">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="mb-0 text-muted">Total Users</h6>
                            <h4 class="mt-2 mb-0">{{ $users->count() }}</h4>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-primary-subtle text-primary rounded fs-3">
                                <i class="fas fa-users"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="mb-0 text-muted">Active</h6>
                            <h4 class="mt-2 mb-0">{{ $users->where('status', 'active')->count() }}</h4>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-success-subtle text-success rounded fs-3">
                                <i class="fas fa-user-check"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="mb-0 text-muted">Super Admin</h6>
                            <h4 class="mt-2 mb-0">{{ $users->filter(fn($u) => $u->hasRole('super-admin'))->count() }}</h4>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-danger-subtle text-danger rounded fs-3">
                                <i class="fas fa-user-shield"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="mb-0 text-muted">Admin</h6>
                            <h4 class="mt-2 mb-0">{{ $users->filter(fn($u) => $u->hasRole('admin'))->count() }}</h4>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-warning-subtle text-warning rounded fs-3">
                                <i class="fas fa-user-tie"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="mb-0 text-muted">Cashiers</h6>
                            <h4 class="mt-2 mb-0">{{ $users->filter(fn($u) => $u->hasRole('cashier'))->count() }}</h4>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-info-subtle text-info rounded fs-3">
                                <i class="fas fa-cash-register"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="mb-0 text-muted">Others</h6>
                            <h4 class="mt-2 mb-0">{{ $users->filter(fn($u) => $u->hasAnyRole(['store-manager', 'accountant', 'auditor']))->count() }}</h4>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-secondary-subtle text-secondary rounded fs-3">
                                <i class="fas fa-users-cog"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-users me-2 text-primary"></i>All Users
                    </h5>
                    <div class="d-flex gap-2">
                        <div class="input-group" style="width: 250px;">
                            <span class="input-group-text bg-white">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text" class="form-control" id="searchUser" placeholder="Search users...">
                        </div>
                        <a href="{{ route('users.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Add User
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" id="usersTable">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col" class="ps-4">#</th>
                                    <th scope="col">User</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Phone</th>
                                    <th scope="col">Role</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Joined</th>
                                    <th scope="col" class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($users as $index => $user)
                                <tr>
                                    <td class="ps-4">{{ $index + 1 }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm me-3">
                                                @if($user->avatar)
                                                    <img src="{{ asset($user->avatar) }}" class="rounded-circle" width="40" height="40" alt="{{ $user->name }}">
                                                @else
                                                    <span class="avatar-title bg-primary-subtle text-primary rounded-circle fs-5">
                                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                                    </span>
                                                @endif
                                            </div>
                                            <div>
                                                <h6 class="mb-0">{{ $user->name }}</h6>
                                                <small class="text-muted">ID: {{ $user->id }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <i class="fas fa-envelope text-muted me-2"></i>
                                        {{ $user->email }}
                                    </td>
                                    <td>
                                        <i class="fas fa-phone text-muted me-2"></i>
                                        {{ $user->phone ?? 'N/A' }}
                                    </td>
                                    <td>
                                        @php
                                            $role = $user->roles->first();
                                            $roleColors = [
                                                'super-admin' => 'danger',
                                                'admin' => 'warning',
                                                'store-manager' => 'primary',
                                                'cashier' => 'info',
                                                'accountant' => 'success',
                                                'auditor' => 'secondary'
                                            ];
                                            $color = $roleColors[$role->name ?? ''] ?? 'secondary';
                                        @endphp
                                        <span class="badge bg-{{ $color }}">
                                            {{ ucwords($role->name ?? 'No Role') }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge {{ $user->status === 'active' ? 'bg-success' : 'bg-secondary' }}">
                                            <i class="fas {{ $user->status === 'active' ? 'fa-check-circle' : 'fa-ban' }} me-1"></i>
                                            {{ ucfirst($user->status ?? 'active') }}
                                        </span>
                                    </td>
                                    <td>
                                        <div>{{ $user->created_at->format('d M Y') }}</div>
                                        <small class="text-muted">{{ $user->created_at->diffForHumans() }}</small>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-1">
                                            <button type="button" class="btn btn-sm btn-info"
                                                    onclick="viewUser({{ $user->id }})"
                                                    title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-success"
                                                    onclick="changeRole({{ $user->id }}, '{{ $role->name ?? '' }}')"
                                                    title="Change Role">
                                                <i class="fas fa-user-tag"></i>
                                            </button>
                                            <a href="{{ route('users.edit', $user->id) }}"
                                               class="btn btn-sm btn-primary"
                                               title="Edit User">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @if($user->id !== auth()->id())
                                            <button type="button" class="btn btn-sm btn-warning"
                                                    onclick="toggleStatus({{ $user->id }}, '{{ $user->status }}')"
                                                    title="Toggle Status">
                                                <i class="fas fa-toggle-on"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger"
                                                    onclick="deleteUser({{ $user->id }})"
                                                    title="Delete User">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            @else
                                            <span class="badge bg-secondary">You</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="fas fa-users fa-3x mb-3 d-block"></i>
                                            <h5>No Users Found</h5>
                                            <p>Start by adding your first user</p>
                                            <a href="{{ route('users.create') }}" class="btn btn-primary mt-2">
                                                <i class="fas fa-plus me-2"></i>Add User
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- View User Modal -->
<div class="modal fade" id="viewUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-user me-2"></i>User Details
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="userDetailsContent">
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Change Role Modal -->
<div class="modal fade" id="changeRoleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">
                    <i class="fas fa-user-tag me-2"></i>Change User Role
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="changeRoleForm">
                <div class="modal-body">
                    <input type="hidden" id="changeRoleUserId">
                    <div class="mb-3">
                        <label class="form-label">Select New Role:</label>
                        <select class="form-select" id="newRole" required>
                            <option value="">Choose a role...</option>
                            <option value="super-admin">Super Admin</option>
                            <option value="admin">Admin</option>
                            <option value="store-manager">Store Manager</option>
                            <option value="cashier">Cashier</option>
                            <option value="accountant">Accountant</option>
                            <option value="auditor">Auditor</option>
                        </select>
                    </div>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Changing the role will update the user's permissions immediately.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check me-2"></i>Update Role
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- CSRF TOKEN  --}}
<meta name="csrf-token" content="{{ csrf_token() }}">

<script>
// Search Users
document.getElementById('searchUser').addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase();
    const rows = document.querySelectorAll('#usersTable tbody tr');

    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchTerm) ? '' : 'none';
    });
});

// View User Details
function viewUser(userId) {
    const modal = new bootstrap.Modal(document.getElementById('viewUserModal'));
    const content = document.getElementById('userDetailsContent');

    content.innerHTML = `
        <div class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    `;

    modal.show();

    fetch(`/users/${userId}`)
        .then(res => {
            if (!res.ok) throw new Error('Failed to fetch');
            return res.json();
        })
        .then(user => {
            const role = user.roles && user.roles.length > 0 ? user.roles[0].name : 'No Role';

            content.innerHTML = `
                <div class="row">
                    <div class="col-md-4 text-center mb-3">
                        ${user.avatar
                            ? `<img src="/${user.avatar}" class="rounded-circle mb-3" width="150" height="150" alt="${user.name}">`
                            : `<div class="avatar-title bg-primary-subtle text-primary rounded-circle mx-auto mb-3" style="width: 150px; height: 150px; font-size: 60px; line-height: 150px;">${user.name.charAt(0).toUpperCase()}</div>`
                        }
                        <h4>${user.name}</h4>
                        <span class="badge ${user.status === 'active' ? 'bg-success' : 'bg-secondary'} mb-2">
                            ${user.status ? user.status.toUpperCase() : 'ACTIVE'}
                        </span>
                    </div>
                    <div class="col-md-8">
                        <h5 class="mb-3">Personal Information</h5>
                        <table class="table table-bordered">
                            <tr>
                                <th width="30%">Email:</th>
                                <td>${user.email}</td>
                            </tr>
                            <tr>
                                <th>Phone:</th>
                                <td>${user.phone || 'N/A'}</td>
                            </tr>
                            <tr>
                                <th>Address:</th>
                                <td>${user.address || 'N/A'}</td>
                            </tr>
                            <tr>
                                <th>Role:</th>
                                <td><span class="badge bg-primary">${role.toUpperCase()}</span></td>
                            </tr>
                            <tr>
                                <th>Joined:</th>
                                <td>${new Date(user.created_at).toLocaleDateString()}</td>
                            </tr>
                            <tr>
                                <th>Last Updated:</th>
                                <td>${new Date(user.updated_at).toLocaleDateString()}</td>
                            </tr>
                        </table>

                        <div class="d-flex gap-2 mt-3">
                            <a href="/users/${user.id}/edit" class="btn btn-primary">
                                <i class="fas fa-edit me-2"></i>Edit User
                            </a>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            `;
        })
        .catch(err => {
            console.error('Error:', err);
            content.innerHTML = `
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    Error loading user details. Please try again.
                </div>
            `;
        });
}

// Change Role
function changeRole(userId, currentRole) {
    document.getElementById('changeRoleUserId').value = userId;
    document.getElementById('newRole').value = currentRole;

    const modal = new bootstrap.Modal(document.getElementById('changeRoleModal'));
    modal.show();
}

document.getElementById('changeRoleForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const userId = document.getElementById('changeRoleUserId').value;
    const newRole = document.getElementById('newRole').value;

    if (!newRole) {
        alert('Please select a role!');
        return;
    }

    fetch(`/users/${userId}/change-role`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ role: newRole })
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            alert(data.message);
            location.reload();
        } else {
            alert(data.message || 'Failed to update role!');
        }
    })
    .catch(err => {
        console.error('Error:', err);
        alert('An error occurred while updating the role.');
    });
});

// Toggle User Status
function toggleStatus(userId, currentStatus) {
    const newStatus = currentStatus === 'active' ? 'inactive' : 'active';

    if (!confirm(`Are you sure you want to ${newStatus === 'active' ? 'activate' : 'deactivate'} this user?`)) {
        return;
    }

    fetch(`/users/${userId}/toggle-status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ status: newStatus })
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            alert(data.message);
            location.reload();
        } else {
            alert(data.message || 'Failed to update status!');
        }
    })
    .catch(err => {
        console.error('Error:', err);
        alert('An error occurred while updating user status.');
    });
}

// Delete User
function deleteUser(userId) {
    if (!confirm('Are you sure you want to delete this user? This action cannot be undone!')) {
        return;
    }

    const btn = event.target.closest('button');
    const originalHtml = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

    fetch(`/users/${userId}/del`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            alert(data.message);
            location.reload();
        } else {
            alert(data.message || 'Failed to delete user!');
            btn.disabled = false;
            btn.innerHTML = originalHtml;
        }
    })
    .catch(err => {
        console.error('Error:', err);
        alert('An error occurred while deleting the user.');
        btn.disabled = false;
        btn.innerHTML = originalHtml;
    });
}
</script>
@endsection
