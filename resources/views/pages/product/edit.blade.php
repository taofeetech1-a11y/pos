@extends("main_master")
@section("main")

<div class="container-xxl">

    <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
        <div class="flex-grow-1">
            <h4 class="fs-18 fw-semibold m-0">Edit Product</h4>
        </div>
        <div>
            <a href="{{ route('products') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Products
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-plus-circle me-2 text-primary"></i>
                        Product Information
                    </h5>
                </div>

                <div class="card-body">
                    @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
                    <form action="{{ route('product.update', $product->id) }}" method="POST" enctype="multipart/form-data" id="productForm">
                        @csrf

                        <div class="row">
                            <!-- Basic Information Section -->
                            <div class="col-12">
                                <h5 class="mb-3 text-primary border-bottom pb-2">
                                    <i class="fas fa-info-circle me-2"></i>Basic Information
                                </h5>
                            </div>

                            <!-- Product Name -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Product Name <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-box"></i>
                                    </span>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $product->name ?? '') }}" placeholder="Enter product name" required>
                                </div>
                                @error('name')
                                <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Product Code -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Product Code <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-barcode"></i>
                                    </span>
                                    <input type="text" class="form-control @error('code') is-invalid @enderror" name="code" value="{{ old('code', $product->code ?? '') }}" placeholder="Enter product code or barcode" required>
                                    <button class="btn btn-outline-secondary" type="button" onclick="generateCode()">
                                        <i class="fas fa-random"></i> Generate
                                    </button>
                                </div>
                                @error('code')
                                <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Category -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Category <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-list"></i>
                                    </span>
                                    <select class="form-select @error('category_id') is-invalid @enderror" name="category_id">
                                        <option value="">Select Category</option>
                                        @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id', $product->category_id ?? '') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('category_id')
                                <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Brand -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Brand <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-tag"></i>
                                    </span>
                                    <select class="form-select @error('brand_id') is-invalid @enderror" name="brand_id">
                                        <option value="">Select Brand</option>
                                        @foreach($brands as $brand)
                                        <option value="{{ $brand->id }}" {{ old('brand_id', $product->brand_id ?? '') == $brand->id ? 'selected' : '' }}>
                                            {{ $brand->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('brand_id')
                                <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Pricing Section -->
                            <div class="col-12 mt-3">
                                <h5 class="mb-3 text-primary border-bottom pb-2">
                                    <i class="fas fa-dollar-sign me-2"></i>Pricing & Discount
                                </h5>
                            </div>

                            <!-- Price -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Price (₦) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">₦</span>
                                    <input type="number" class="form-control @error('price') is-invalid @enderror" name="price" value="{{ old('price', $product->price ?? '') }}" placeholder="0.00" step="0.01" min="0" required>
                                </div>
                                @error('price')
                                <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Discount -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Discount (%)</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-percent"></i>
                                    </span>
                                    <input type="number" class="form-control @error('discount') is-invalid @enderror" name="discount" id="discount" value="{{ old('discount', $product->discount ?? 0) }}" placeholder="0" step="0.01" min="0" max="100">
                                    <span class="input-group-text" id="discountedPrice">Final: ₦0.00</span>
                                </div>
                                @error('discount')
                                <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Stock Section -->
                            <div class="col-12 mt-3">
                                <h5 class="mb-3 text-primary border-bottom pb-2">
                                    <i class="fas fa-warehouse me-2"></i>Stock Information
                                </h5>
                            </div>

                            @if(isset($product))
                            <!-- Current Stock (Read-only in edit mode) -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Current Stock Quantity</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-cubes"></i>
                                    </span>
                                    <input type="text" class="form-control bg-light" value="{{ $product->product_qty }}" readonly>
                                    <span class="input-group-text">
                                        @if($product->product_qty == 0)
                                        <span class="badge bg-danger">Out of Stock</span>
                                        @elseif($product->product_qty <= $product->stock_alert)
                                            <span class="badge bg-warning">Low Stock</span>
                                            @else
                                            <span class="badge bg-success">In Stock</span>
                                            @endif
                                    </span>
                                </div>
                                <small class="text-muted">Stock is managed through Product Supplies</small>
                            </div>
                            @else
                            <div class="col-md-6 mb-3">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Product will start with <strong>0 quantity</strong>. Add stock via Product Supplies after creation.
                                </div>
                            </div>
                            @endif

                            <!-- Stock Alert -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Stock Alert Level <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-exclamation-triangle"></i>
                                    </span>
                                    <input type="number" class="form-control @error('stock_alert') is-invalid @enderror" name="stock_alert" value="{{ old('stock_alert', $product->stock_alert ?? 10) }}" placeholder="10" min="0" required>
                                </div>
                                <small class="text-muted">Alert when stock reaches this level</small>
                                @error('stock_alert')
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
                                        <option value="available" {{ old('status', $product->status ?? '') === 'available' ? 'selected' : '' }}>
                                            Available
                                        </option>
                                        <option value="unavailable" {{ old('status', $product->status ?? '') === 'unavailable' ? 'selected' : '' }}>
                                            Unavailable
                                        </option>
                                        <option value="on_hold" {{ old('status', $product->status ?? '') === 'on_hold' ? 'selected' : '' }}>
                                            On Hold
                                        </option>
                                    </select>
                                </div>
                                @error('status')
                                <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Product Images -->
                            <div class="col-12 mt-3">
                                <h5 class="mb-3 text-primary border-bottom pb-2">
                                    <i class="fas fa-images me-2"></i>Product Images
                                </h5>
                            </div>

                            <!-- Existing Images (Edit Mode) -->
                            @if(isset($product) && $product->images->count() > 0)
                            <div class="col-12 mb-3">
                                <label class="form-label">Current Images</label>
                                <div class="d-flex flex-wrap gap-2" id="existingImages">
                                    @foreach($product->images as $image)
                                    <div class="position-relative" id="image-{{ $image->id }}">
                                        <img src="{{ asset($image->url) }}" class="rounded border" width="120" height="120" alt="Product Image">
                                        <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0" onclick="removeExistingImage({{ $image->id }})" style="margin: 5px;">
                                            <i class="fas fa-times"></i>
                                        </button>
                                        <input type="hidden" name="remove_images[]" value="" id="remove-{{ $image->id }}">
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            <!-- Upload New Images -->
                            <div class="col-12 mb-3">
                                <label class="form-label">{{ isset($product) ? 'Add New Images' : 'Upload Images' }}</label>
                                <input type="file" class="form-control @error('images') is-invalid @enderror" name="images[]" id="productImages" accept="image/*" multiple onchange="previewImages(event)">
                                <small class="text-muted">You can select multiple images. Max 2MB per image.</small>
                                @error('images')
                                <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Image Preview -->
                            <div class="col-12 mb-3">
                                <div class="d-flex flex-wrap gap-2" id="imagePreview"></div>
                            </div>

                            <!-- Notes -->
                            <div class="col-12 mt-3">
                                <h5 class="mb-3 text-primary border-bottom pb-2">
                                    <i class="fas fa-sticky-note me-2"></i>Additional Information
                                </h5>
                            </div>

                            <div class="col-12 mb-3">
                                <label class="form-label">Notes</label>
                                <textarea class="form-control @error('note') is-invalid @enderror" name="note" rows="4" placeholder="Enter any additional notes about this product">{{ old('note', $product->note ?? '') }}</textarea>
                                @error('note')
                                <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('products') }}" class="btn btn-secondary px-4">
                                        <i class="fas fa-times me-2"></i>Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary px-4" id="submitBtn">
                                        <i class="fas fa-plus-circle me-2"></i>
                                        Update Product
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
    function generateCode() {
        const code = 'PRD-' + Date.now().toString().slice(-8);
        document.querySelector('input[name="code"]').value = code;
    }


    function previewImages(event) {
        const preview = document.getElementById('imagePreview');
        preview.innerHTML = '';

        const files = event.target.files;

        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            const reader = new FileReader();

            reader.onload = function(e) {
                const div = document.createElement('div');
                div.className = 'position-relative';
                div.innerHTML = `
                <img src="${e.target.result}" class="rounded border" width="120" height="120" alt="Preview">
                <button type="button"
                        class="btn btn-danger btn-sm position-absolute top-0 end-0"
                        onclick="this.parentElement.remove()"
                        style="margin: 5px;">
                    <i class="fas fa-times"></i>
                </button>
            `;
                preview.appendChild(div);
            }

            reader.readAsDataURL(file);
        }
    }


    function removeExistingImage(imageId) {
        if (confirm('Are you sure you want to remove this image?')) {
            document.getElementById('image-' + imageId).style.opacity = '0.3';
            document.getElementById('remove-' + imageId).value = imageId;

            // Add a red overlay
            const overlay = document.createElement('div');
            overlay.className = 'position-absolute top-0 start-0 w-100 h-100 bg-danger';
            overlay.style.opacity = '0.3';
            document.getElementById('image-' + imageId).appendChild(overlay);
        }
    }

    // Calculate Discounted Price
    document.addEventListener('DOMContentLoaded', function() {
        const priceInput = document.querySelector('input[name="price"]');
        const discountInput = document.getElementById('discount');
        const discountedPriceSpan = document.getElementById('discountedPrice');

        function calculateDiscount() {
            const price = parseFloat(priceInput.value) || 0;
            const discount = parseFloat(discountInput.value) || 0;
            const finalPrice = price - (price * discount / 100);

            discountedPriceSpan.textContent = `Final: ₦${finalPrice.toFixed(2)}`;

            if (discount > 0) {
                discountedPriceSpan.classList.add('text-success', 'fw-bold');
            } else {
                discountedPriceSpan.classList.remove('text-success', 'fw-bold');
            }
        }

        priceInput.addEventListener('input', calculateDiscount);
        discountInput.addEventListener('input', calculateDiscount);

        // Calculate on page load
        calculateDiscount();
    });

    // Form Submission
    document.getElementById('productForm').addEventListener('submit', function(e) {
        const submitBtn = document.getElementById('submitBtn');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Processing...';
    });

</script>
@endsection
