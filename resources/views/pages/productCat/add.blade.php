@extends("main_master")
@section("main")

<div class="container-xxl">

    <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
        <div class="flex-grow-1">
            <h4 class="fs-18 fw-semibold m-0">Create Product Category</h4>
        </div>
        <div>
            <a href="{{ route('brands') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Categories
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-shopping-cart me-2 text-primary"></i>Add Category Information'
                    </h5>
                </div>

                <div class="card-body">
                    <form action="{{ route('category.store') }}"
                          method="POST">
                        @csrf

                        <div class="row">

                            <!-- Personal Information -->
                            <div class="col-12">
                                <h5 class="mb-3 text-primary">
                                    <i class="fas fa-user-circle me-2"></i>Category Information
                                </h5>
                            </div>

                            <!-- Name -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Category Name <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-shopping-cart"></i>
                                    </span>
                                    <input type="text"
                                           class="form-control @error('name') is-invalid @enderror"
                                           name="name"
                                           placeholder="Enter category name"
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
