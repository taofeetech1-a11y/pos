@extends("main_master")
@section("main")

<div class="container-xxl">

    <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
        <div class="flex-grow-1">
            <h4 class="fs-18 fw-semibold m-0">Product Management</h4>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('product.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Add Product
            </a>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-3">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="mb-0 text-muted">Total Products</h6>
                            <h4 class="mt-2 mb-0">{{ $products->count() }}</h4>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-primary-subtle text-primary rounded fs-3">
                                <i class="fas fa-box"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="mb-0 text-muted">Available</h6>
                            <h4 class="mt-2 mb-0">{{ $products->where('status', 'available')->count() }}</h4>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-success-subtle text-success rounded fs-3">
                                <i class="fas fa-check-circle"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="mb-0 text-muted">Low Stock</h6>
                            <h4 class="mt-2 mb-0">{{ $products->filter(fn($p) => $p->product_qty <= $p->stock_alert && $p->product_qty > 0)->count() }}</h4>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-warning-subtle text-warning rounded fs-3">
                                <i class="fas fa-exclamation-triangle"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="mb-0 text-muted">Out of Stock</h6>
                            <h4 class="mt-2 mb-0">{{ $products->where('product_qty', 0)->count() }}</h4>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-danger-subtle text-danger rounded fs-3">
                                <i class="fas fa-times-circle"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Products Table -->
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">All Products</h5>
                    <div class="d-flex gap-2">
                        <input type="text" id="searchProduct" class="form-control form-control-sm" style="width: 200px;" placeholder="Search products...">
                        <select id="filterCategory" class="form-select form-select-sm" style="width: auto;">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        <select id="filterStatus" class="form-select form-select-sm" style="width: auto;">
                            <option value="">All Status</option>
                            <option value="available">Available</option>
                            <option value="unavailable">Unavailable</option>
                            <option value="on_hold">On Hold</option>
                        </select>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle" id="productsTable">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Product</th>
                                    <th>Code</th>
                                    <th>Category</th>
                                    <th>Brand</th>
                                    <th>Price</th>
                                    <th>Stock</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($products as $index => $product)
                                <tr data-category="{{ $product->category_id }}" data-status="{{ $product->status }}">
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="me-3">
                                                @if($product->images && $product->images->count() > 0)
                                                <img src="{{ asset($product->images->first()->url) }}" class="rounded" width="50" height="50" alt="{{ $product->name }}">
                                                @else
                                                <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                                    <i class="fas fa-box text-muted"></i>
                                                </div>
                                                @endif
                                            </div>
                                            <div>
                                                <h6 class="mb-0">{{ $product->name }}</h6>
                                                @if($product->discount > 0)
                                                <small class="text-danger">
                                                    <i class="fas fa-tag"></i> {{ $product->discount }}% OFF
                                                </small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $product->code }}</span>
                                    </td>
                                    <td>{{ $product->category->name ?? 'N/A' }}</td>
                                    <td>{{ $product->brand->name ?? 'N/A' }}</td>
                                    <td>
                                        <div>
                                            <strong>₦{{ number_format($product->price, 2) }}</strong>
                                            @if($product->discount > 0)
                                            <div>
                                                <small class="text-muted text-decoration-line-through">
                                                    ₦{{ number_format($product->price / (1 - $product->discount/100), 2) }}
                                                </small>
                                            </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        @if($product->product_qty == 0)
                                        <span class="badge bg-danger">
                                            <i class="fas fa-times-circle me-1"></i>Out of Stock
                                        </span>
                                        @elseif($product->product_qty <= $product->stock_alert)
                                            <span class="badge bg-warning">
                                                <i class="fas fa-exclamation-triangle me-1"></i>{{ $product->product_qty }}
                                            </span>
                                            @else
                                            <span class="badge bg-success">
                                                <i class="fas fa-check-circle me-1"></i>{{ $product->product_qty }}
                                            </span>
                                            @endif
                                            <div><small class="text-muted">Alert: {{ $product->stock_alert }}</small></div>
                                    </td>
                                    <td>
                                        <span class="badge
                                            @if($product->status === 'available') bg-success
                                            @elseif($product->status === 'unavailable') bg-danger
                                            @else bg-warning
                                            @endif">
                                            {{ ucfirst(str_replace('_', ' ', $product->status)) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-sm btn-info" onclick="viewProduct({{ $product->id }})" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <a href="{{ route('product.edit', $product->id) }}" class="btn btn-sm btn-primary" title="Edit Product">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-danger" onclick="deleteProduct({{ $product->id }})" title="Delete Product">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center text-muted py-4">
                                        <i class="fas fa-box-open fa-3x mb-3 d-block"></i>
                                        No products found. <a href="{{ route('product.create') }}">Add your first product</a>
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

<!-- Product Details Modal -->
<div class="modal fade" id="productDetailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-box me-2"></i>Product Details
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="productDetailsContent">
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    // Search Products
    document.getElementById('searchProduct').addEventListener('input', function() {
        filterProducts();
    });

    // Filter by Category
    document.getElementById('filterCategory').addEventListener('change', function() {
        filterProducts();
    });

    // Filter by Status
    document.getElementById('filterStatus').addEventListener('change', function() {
        filterProducts();
    });

    function filterProducts() {
        const searchTerm = document.getElementById('searchProduct').value.toLowerCase();
        const categoryFilter = document.getElementById('filterCategory').value;
        const statusFilter = document.getElementById('filterStatus').value;
        const rows = document.querySelectorAll('#productsTable tbody tr[data-category]');

        let visibleCount = 0;

        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            const rowCategory = row.getAttribute('data-category');
            const rowStatus = row.getAttribute('data-status');

            let showRow = true;

            // Search filter
            if (searchTerm && !text.includes(searchTerm)) {
                showRow = false;
            }

            // Category filter
            if (categoryFilter && rowCategory !== categoryFilter) {
                showRow = false;
            }

            // Status filter
            if (statusFilter && rowStatus !== statusFilter) {
                showRow = false;
            }

            if (showRow) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });
    }

    // View Product Details
    function viewProduct(productId) {
        const modal = new bootstrap.Modal(document.getElementById('productDetailsModal'));
        const content = document.getElementById('productDetailsContent');

        content.innerHTML = `
        <div class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
        `;

        modal.show();

        fetch(`/product/${productId}`)
            .then(res => {
                if (!res.ok) throw new Error('Failed to fetch');
                return res.json();
            })
            .then(product => {
                let imagesHtml = '';
                if (product.images && product.images.length > 0) {
                    imagesHtml = product.images.map(img =>
                        `<img src="/${img.url}" class="rounded me-2 mb-2" width="100" height="100" alt="${product.name}">`
                    ).join('');
                } else {
                    imagesHtml = '<div class="text-muted">No images available</div>';
                }

                const stockBadge = product.product_qty == 0 ?
                    '<span class="badge bg-danger"><i class="fas fa-times-circle me-1"></i>Out of Stock</span>' :
                    product.product_qty <= product.stock_alert ?
                    '<span class="badge bg-warning"><i class="fas fa-exclamation-triangle me-1"></i>Low Stock</span>' :
                    '<span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>In Stock</span>';

                content.innerHTML = `
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <h5 class="mb-2">Product Images</h5>
                        <div class="d-flex flex-wrap">
                            ${imagesHtml}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h5 class="mb-3">Product Information</h5>
                        <table class="table table-bordered">
                            <tr>
                                <th width="40%">Product Name:</th>
                                <td>${product.name}</td>
                            </tr>
                            <tr>
                                <th>Product Code:</th>
                                <td><span class="badge bg-secondary">${product.code}</span></td>
                            </tr>
                            <tr>
                                <th>Category:</th>
                                <td>${product.category ? product.category.name : 'N/A'}</td>
                            </tr>
                            <tr>
                                <th>Brand:</th>
                                <td>${product.brand ? product.brand.name : 'N/A'}</td>
                            </tr>
                            <tr>
                                <th>Price:</th>
                                <td><strong class="text-success">₦${parseFloat(product.price).toFixed(2)}</strong></td>
                            </tr>
                            <tr>
                                <th>Discount:</th>
                                <td>${product.discount > 0 ? `<span class="badge bg-danger">${product.discount}% OFF</span>` : 'No discount'}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h5 class="mb-3">Stock Information</h5>
                        <table class="table table-bordered">
                            <tr>
                                <th width="40%">Current Stock:</th>
                                <td><strong>${product.product_qty}</strong> ${stockBadge}</td>
                            </tr>
                            <tr>
                                <th>Stock Alert:</th>
                                <td>${product.stock_alert}</td>
                            </tr>
                            <tr>
                                <th>Status:</th>
                                <td>
                                    <span class="badge ${product.status === 'available' ? 'bg-success' : product.status === 'unavailable' ? 'bg-danger' : 'bg-warning'}">
                                        ${product.status.toUpperCase().replace('_', ' ')}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Created:</th>
                                <td>${new Date(product.created_at).toLocaleDateString()}</td>
                            </tr>
                            <tr>
                                <th>Last Updated:</th>
                                <td>${new Date(product.updated_at).toLocaleDateString()}</td>
                            </tr>
                        </table>
                    </div>
                    ${product.note ? `
                    <div class="col-md-12 mt-3">
                        <h5 class="mb-2">Notes</h5>
                        <div class="alert alert-info">
                            ${product.note}
                        </div>
                    </div>
                    ` : ''}
                </div>

                <div class="d-flex gap-2 mt-3">
                    <a href="/product/${product.id}/edit" class="btn btn-primary">
                        <i class="fas fa-edit me-2"></i>Edit Product
                    </a>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            `;
            })
            .catch(err => {
                console.error('Error:', err);
                content.innerHTML = `
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    Error loading product details. Please try again.
                </div>
            `;
            });
    }

    // Delete Product
    function deleteProduct(productId) {
        if (!confirm('Are you sure you want to delete this product? This action cannot be undone!')) {
            return;
        }

        const btn = event.target.closest('button');
        const originalHtml = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

        fetch(`/product/${productId}`, {
                method: 'DELETE'
                , headers: {
                    'Content-Type': 'application/json'
                    , 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    alert(data.message);
                    location.reload();
                } else {
                    alert(data.message || 'Failed to delete product!');
                    btn.disabled = false;
                    btn.innerHTML = originalHtml;
                }
            })
            .catch(err => {
                console.error('Error:', err);
                alert('An error occurred while deleting the product.');
                btn.disabled = false;
                btn.innerHTML = originalHtml;
            });
    }

</script>
@endsection
