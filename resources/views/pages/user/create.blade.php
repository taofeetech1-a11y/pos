@extends("main_master")
@section("main")

<div class="container-xxl">

    <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
        <div class="flex-grow-1">
            <h4 class="fs-18 fw-semibold m-0">Add New User</h4>
        </div>
        <div>
            <a href="{{ route('users.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Users
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-user-plus me-2 text-primary"></i>
                        User Information
                    </h5>
                </div>

                <div class="card-body">
                    <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data" id="userForm">
                        @csrf

                        <div class="row">
                            <!-- Avatar Upload -->
                            <div class="col-md-12 mb-4">
                                <div class="text-center">
                                    <label class="form-label d-block">Profile Picture</label>
                                    <div class="mb-3">
                                        <div id="avatarPreview" class="rounded-circle bg-primary-subtle text-primary mx-auto d-flex align-items-center justify-content-center" style="width: 120px; height: 120px; font-size: 48px;">
                                            <i class="fas fa-user"></i>
                                        </div>
                                    </div>
                                    <input type="file" class="form-control w-50 mx-auto" id="avatar" name="avatar" accept="image/*" onchange="previewAvatar(event)">
                                    <small class="text-muted">Recommended: Square image, max 2MB</small>
                                    @error('avatar')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Personal Information -->
                            <div class="col-12">
                                <h5 class="mb-3 text-primary">
                                    <i class="fas fa-user-circle me-2"></i>Personal Information
                                </h5>
                            </div>

                            <!-- Name -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Full Name <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-user"></i>
                                    </span>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $user->name ?? '') }}" placeholder="Enter full name" required>
                                </div>
                                @error('name')
                                <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email Address <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-envelope"></i>
                                    </span>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', $user->email ?? '') }}" placeholder="Enter email address" required>
                                </div>
                                @error('email')
                                <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Phone -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-phone"></i>
                                    </span>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone', $user->phone ?? '') }}" placeholder="Enter phone number" required>
                                </div>
                                @error('phone')
                                <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Status <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-toggle-on"></i>
                                    </span>
                                    <select class="form-select @error('status') is-invalid @enderror" name="status" required>
                                        <option value="">Select Status</option>
                                        <option value="active" {{ old('status', $user->status ?? '') === 'active' ? 'selected' : '' }}>
                                            Active
                                        </option>
                                        <option value="inactive" {{ old('status', $user->status ?? '') === 'inactive' ? 'selected' : '' }}>
                                            Inactive
                                        </option>
                                    </select>
                                </div>
                                @error('status')
                                <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Address -->
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Address</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </span>
                                    <textarea class="form-control @error('address') is-invalid @enderror" name="address" rows="2" placeholder="Enter complete address">{{ old('address', $user->address ?? '') }}</textarea>
                                </div>
                                @error('address')
                                <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Role & Permissions -->
                            <div class="col-12 mt-3">
                                <h5 class="mb-3 text-primary">
                                    <i class="fas fa-user-shield me-2"></i>Role & Permissions
                                </h5>
                            </div>

                            <!-- Role Selection -->
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Assign Role <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-user-tag"></i>
                                    </span>
                                    <select class="form-select @error('role') is-invalid @enderror" name="role" id="roleSelect" required>
                                        <option value="">Select Role</option>
                                        @foreach($roles as $role)
                                        <option value="{{ $role->name }}" {{ old('role', isset($user) && $user->roles->first() ? $user->roles->first()->name : '') === $role->name ? 'selected' : '' }}>
                                            {{ ucwords($role->name) }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('role')
                                <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Role determines what actions the user can perform</small>
                            </div>

                            <!-- Role Description -->
                            <div class="col-md-12 mb-3">
                                <div class="alert alert-info" id="roleDescription">
                                    <strong>Role Descriptions:</strong>
                                    <ul class="mb-0 mt-2">
                                        <li><strong>Super Admin:</strong> Full system access and control</li>
                                        <li><strong>Admin:</strong> Manage users, products, and sales</li>
                                        <li><strong>Store Manager:</strong> Manage inventory and staff</li>
                                        <li><strong>Cashier:</strong> Process sales and manage POS</li>
                                        <li><strong>Accountant:</strong> Financial reports and accounting</li>
                                        <li><strong>Auditor:</strong> View-only access for auditing</li>
                                    </ul>
                                </div>
                            </div>

                            <!-- Password Section -->
                            <div class="col-12 mt-3">
                                <h5 class="mb-3 text-primary">
                                    <i class="fas fa-lock me-2"></i>
                                    {{ isset($user) ? 'Change Password (Leave blank to keep current)' : 'Password' }}
                                </h5>
                            </div>

                            <!-- Password -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    Password
                                    @if(!isset($user))
                                    <span class="text-danger">*</span>
                                    @endif
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" id="password" placeholder="Enter password" {{ !isset($user) ? 'required' : '' }}>
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password')">
                                        <i class="fas fa-eye" id="password-icon"></i>
                                    </button>
                                </div>
                                @error('password')
                                <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                                @if(!isset($user))
                                <small class="text-muted">Minimum 8 characters</small>
                                @endif
                            </div>

                            <!-- Confirm Password -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    Confirm Password
                                    @if(!isset($user))
                                    <span class="text-danger">*</span>
                                    @endif
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                    <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" name="password_confirmation" id="password_confirmation" placeholder="Confirm password" {{ !isset($user) ? 'required' : '' }}>
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password_confirmation')">
                                        <i class="fas fa-eye" id="password_confirmation-icon"></i>
                                    </button>
                                </div>
                                @error('password_confirmation')
                                <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('users.index') }}" class="btn btn-secondary px-4">
                                        <i class="fas fa-times me-2"></i>Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary px-4" id="submitBtn">
                                        <i class="fas {{ isset($user) ? 'fa-save' : 'fa-user-plus' }} me-2"></i>
                                        {{ isset($user) ? 'Update User' : 'Create User' }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
    // Preview Avatar
    function previewAvatar(event) {
        const file = event.target.files[0];
        const preview = document.getElementById('avatarPreview');

        if (file) {
            const reader = new FileReader();

            reader.onload = function(e) {
                preview.innerHTML = `<img src="${e.target.result}" class="rounded-circle" width="120" height="120" alt="Avatar Preview">`;
            }

            reader.readAsDataURL(file);
        }
    }

    // Toggle Password Visibility
    function togglePassword(fieldId) {
        const field = document.getElementById(fieldId);
        const icon = document.getElementById(fieldId + '-icon');

        if (field.type === 'password') {
            field.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            field.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }

    // Form Submission
    document.getElementById('userForm').addEventListener('submit', function(e) {
        const submitBtn = document.getElementById('submitBtn');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Processing...';
    });

    // Password Match Validation
    document.getElementById('password_confirmation').addEventListener('input', function() {
        const password = document.getElementById('password').value;
        const confirmation = this.value;

        if (password && confirmation && password !== confirmation) {
            this.setCustomValidity('Passwords do not match');
            this.classList.add('is-invalid');
        } else {
            this.setCustomValidity('');
            this.classList.remove('is-invalid');
        }
    });

</script>
@endsection
