@extends("main_master")
@section("main")

<div class="container-xxl">

    <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
        <div class="flex-grow-1">
            <h4 class="fs-18 fw-semibold m-0">Create Brand</h4>
        </div>
        <div>
            <a href="{{ route('brands') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Brands
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-shopping-cart me-2 text-primary"></i>Add Brand Information'
                    </h5>
                </div>

                <div class="card-body">
                    <form action="{{ route('brand.store') }}"
                          method="POST">
                        @csrf

                        <div class="row">

                            <!-- Personal Information -->
                            <div class="col-12">
                                <h5 class="mb-3 text-primary">
                                    <i class="fas fa-user-circle me-2"></i>Brand Information
                                </h5>
                            </div>

                            <!-- Name -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Brand Name <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-shopping-cart"></i>
                                    </span>
                                    <input type="text"
                                           class="form-control @error('name') is-invalid @enderror"
                                           name="name"
                                           placeholder="Enter brand name"
                                           required>
                                </div>
                                @error('name')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('brands') }}" class="btn btn-secondary px-4">
                                        <i class="fas fa-times me-2"></i>Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary px-4" id="submitBtn">
                                        <i class="fas fa-save me-2"></i>Save 
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

@endsection

@section('scripts')
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
