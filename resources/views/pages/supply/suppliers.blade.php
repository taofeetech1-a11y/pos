@extends("main_master")
@section("main")

<div class="container-xxl">

    <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
        <div class="flex-grow-1">
            <h4 class="fs-18 fw-semibold m-0">Supplier Management</h4>
        </div>
        <div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#supplierModal" onclick="openAddModal()">
                <i class="fas fa-plus me-2"></i>Add Supplier
            </button>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-3">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="mb-0 text-muted">Total Suppliers</h6>
                            <h4 class="mt-2 mb-0">{{ $suppliers->count() }}</h4>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-primary-subtle text-primary rounded fs-3">
                                <i class="fas fa-truck"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="mb-0 text-muted">Active Supplies</h6>
                            <h4 class="mt-2 mb-0">{{ $suppliers->sum(fn($s) => $s->productSupplies->where('status', 'approved')->count()) }}</h4>
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
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="mb-0 text-muted">Total Products Supplied</h6>
                            <h4 class="mt-2 mb-0">{{ $suppliers->sum(fn($s) => $s->productSupplies->sum('qty_supplied')) }}</h4>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-info-subtle text-info rounded fs-3">
                                <i class="fas fa-boxes"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Suppliers Table -->
    <div class="row">
        <div class="col-xl-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-truck me-2 text-primary"></i>All Suppliers
                    </h5>
                    <div>
                        <input type="text" id="searchSupplier" class="form-control form-control-sm" style="width: 250px;" placeholder="Search suppliers...">
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" id="suppliersTable">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col" class="ps-4">#</th>
                                    <th scope="col">Supplier</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Phone</th>
                                    <th scope="col">Address</th>
                                    <th scope="col">Supplies</th>
                                    <th scope="col">Joined</th>
                                    <th scope="col" class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($suppliers as $index => $supplier)
                                <tr>
                                    <td class="ps-4">{{ $index + 1 }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="me-3">
                                                @if($supplier->image)
                                                <img src="{{ asset($supplier->image) }}" class="rounded-circle" width="45" height="45" alt="{{ $supplier->name }}">
                                                @else
                                                <div class="avatar-sm">
                                                    <span class="avatar-title bg-primary-subtle text-primary rounded-circle fs-5">
                                                        {{ strtoupper(substr($supplier->name, 0, 1)) }}
                                                    </span>
                                                </div>
                                                @endif
                                            </div>
                                            <div>
                                                <h6 class="mb-0">{{ $supplier->name }}</h6>
                                                <small class="text-muted">ID: {{ $supplier->id }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <i class="fas fa-envelope text-muted me-2"></i>
                                        {{ $supplier->email ?? 'N/A' }}
                                    </td>
                                    <td>
                                        <i class="fas fa-phone text-muted me-2"></i>
                                        {{ $supplier->phone }}
                                    </td>
                                    <td>
                                        <i class="fas fa-map-marker-alt text-muted me-2"></i>
                                        {{ Str::limit($supplier->address, 30) }}
                                    </td>
                                    <td>
                                        <span class="badge bg-info">
                                            {{ $supplier->productSupplies->count() }} supply(s)
                                        </span>
                                    </td>
                                    <td>
                                        <div>{{ $supplier->created_at->format('d M Y') }}</div>
                                        <small class="text-muted">{{ $supplier->created_at->diffForHumans() }}</small>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-1">
                                            <button type="button" class="btn btn-sm btn-info" onclick="viewSupplier({{ $supplier->id }})" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-primary" onclick="editSupplier({{ $supplier->id }})" title="Edit Supplier">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger" onclick="deleteSupplier({{ $supplier->id }})" title="Delete Supplier">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="fas fa-truck fa-3x mb-3 d-block"></i>
                                            <h5>No Suppliers Found</h5>
                                            <p>Start by adding your first supplier</p>
                                            <button class="btn btn-primary mt-2" data-bs-toggle="modal" data-bs-target="#supplierModal" onclick="openAddModal()">
                                                <i class="fas fa-plus me-2"></i>Add Supplier
                                            </button>
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

<!-- Add/Edit Supplier Modal -->
<div class="modal fade" id="supplierModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalTitle">
                    <i class="fas fa-truck me-2"></i>Add Supplier
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="supplierForm" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="supplierId" name="supplier_id">
                <input type="hidden" id="formMethod" name="_method">

                <div class="modal-body">
                    <div class="row">
                        <!-- Supplier Image -->
                        <div class="col-12 text-center mb-4">
                            <label class="form-label">Supplier Logo/Image</label>
                            <div class="mb-2">
                                <div id="imagePreview" class="rounded-circle bg-light mx-auto d-flex align-items-center justify-content-center" style="width: 120px; height: 120px;">
                                    <i class="fas fa-truck fa-3x text-muted"></i>
                                </div>
                            </div>
                            <input type="file" class="form-control w-50 mx-auto" id="supplierImage" name="image" accept="image/*" onchange="previewImage(event)">
                            <small class="text-muted">Optional: Square image, max 2MB</small>
                        </div>

                        <!-- Supplier Name -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Supplier Name <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-building"></i></span>
                                <input type="text" class="form-control" id="supplierName" name="name" placeholder="Enter supplier name" required>
                            </div>
                            <div class="invalid-feedback"></div>
                        </div>

                        <!-- Email -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                <input type="email" class="form-control" id="supplierEmail" name="email" placeholder="supplier@example.com">
                            </div>
                            <div class="invalid-feedback"></div>
                        </div>

                        <!-- Phone -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                <input type="text" class="form-control" id="supplierPhone" name="phone" placeholder="+234 123 456 7890" required>
                            </div>
                            <div class="invalid-feedback"></div>
                        </div>

                        <!-- Address -->
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Address <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                <textarea class="form-control" id="supplierAddress" name="address" rows="2" placeholder="Enter complete address" required></textarea>
                            </div>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <i class="fas fa-save me-2"></i>Save Supplier
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Supplier Modal -->
<div class="modal fade" id="viewSupplierModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">
                    <i class="fas fa-info-circle me-2"></i>Supplier Details
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="viewSupplierContent">
                <div class="text-center py-5">
                    <div class="spinner-border text-primary"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<meta name="csrf-token" content="{{ csrf_token() }}">

<script>
    // Search Suppliers
    document.getElementById('searchSupplier').addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const rows = document.querySelectorAll('#suppliersTable tbody tr');

        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchTerm) ? '' : 'none';
        });
    });

    // Preview Image
    function previewImage(event) {
        const preview = document.getElementById('imagePreview');
        const file = event.target.files[0];

        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.innerHTML = `<img src="${e.target.result}" class="rounded-circle" width="120" height="120" alt="Preview">`;
            }
            reader.readAsDataURL(file);
        }
    }

    // Open Add Modal
    function openAddModal() {
        document.getElementById('modalTitle').innerHTML = '<i class="fas fa-truck me-2"></i>Add Supplier';
        document.getElementById('supplierForm').reset();
        document.getElementById('supplierId').value = '';
        document.getElementById('formMethod').value = '';
        document.getElementById('imagePreview').innerHTML = '<i class="fas fa-truck fa-3x text-muted"></i>';
        document.getElementById('submitBtn').innerHTML = '<i class="fas fa-save me-2"></i>Save Supplier';

        // Clear validation errors
        document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
    }

    // Edit Supplier
    function editSupplier(supplierId) {
    fetch(`/supplier/${supplierId}`)
        .then(res => res.json())
        .then(supplier => {
            document.getElementById('modalTitle').innerHTML =
                '<i class="fas fa-edit me-2"></i>Edit Supplier';

            document.getElementById('supplierId').value = supplier.id;
            document.getElementById('formMethod').value = 'PUT';

            document.getElementById('supplierName').value = supplier.name;
            document.getElementById('supplierEmail').value = supplier.email ?? '';
            document.getElementById('supplierPhone').value = supplier.phone;
            document.getElementById('supplierAddress').value = supplier.address;

            if (supplier.image) {
                document.getElementById('imagePreview').innerHTML =
                    `<img src="/${supplier.image}" class="rounded-circle" width="120" height="120">`;
            }

            document.getElementById('submitBtn').innerHTML =
                '<i class="fas fa-save me-2"></i>Update Supplier';

            new bootstrap.Modal(document.getElementById('supplierModal')).show();
        })
        .catch(() => alert('Failed to load supplier'));
}


    // View Supplier
    function viewSupplier(supplierId) {
        const modal = new bootstrap.Modal(document.getElementById('viewSupplierModal'));
        const content = document.getElementById('viewSupplierContent');

        content.innerHTML = '<div class="text-center py-5"><div class="spinner-border text-primary"></div></div>';
        modal.show();

        fetch(`/suppliers/${supplierId}`)
            .then(res => res.json())
            .then(supplier => {
                let suppliesHtml = '';
                if (supplier.product_supplies && supplier.product_supplies.length > 0) {
                    suppliesHtml = supplier.product_supplies.map(supply => `
                    <tr>
                        <td>${supply.product ? supply.product.name : 'N/A'}</td>
                        <td class="text-center">${supply.qty_supplied}</td>
                        <td>₦${parseFloat(supply.unit_price).toFixed(2)}</td>
                        <td>${new Date(supply.created_at).toLocaleDateString()}</td>
                        <td>
                            <span class="badge ${supply.status === 'approved' ? 'bg-success' : supply.status === 'pending' ? 'bg-warning' : 'bg-danger'}">
                                ${supply.status.toUpperCase()}
                            </span>
                        </td>
                    </tr>
                `).join('');
                } else {
                    suppliesHtml = '<tr><td colspan="5" class="text-center text-muted">No supplies yet</td></tr>';
                }

                content.innerHTML = `
                <div class="row">
                    <div class="col-md-4 text-center mb-3">
                        ${supplier.image
                            ? `<img src="/${supplier.image}" class="rounded-circle mb-3" width="150" height="150" alt="${supplier.name}">`
                            : `<div class="avatar-title bg-primary-subtle text-primary rounded-circle mx-auto mb-3" style="width: 150px; height: 150px; font-size: 60px; line-height: 150px;">${supplier.name.charAt(0).toUpperCase()}</div>`
                        }
                        <h4>${supplier.name}</h4>
                    </div>
                    <div class="col-md-8">
                        <h5 class="mb-3">Contact Information</h5>
                        <table class="table table-bordered">
                            <tr>
                                <th width="30%">Email:</th>
                                <td>${supplier.email || 'N/A'}</td>
                            </tr>
                            <tr>
                                <th>Phone:</th>
                                <td>${supplier.phone}</td>
                            </tr>
                            <tr>
                                <th>Address:</th>
                                <td>${supplier.address}</td>
                            </tr>
                            <tr>
                                <th>Joined:</th>
                                <td>${new Date(supplier.created_at).toLocaleDateString()}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <h5 class="mt-4 mb-3">Supply History</h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm">
                        <thead class="table-light">
                            <tr>
                                <th>Product</th>
                                <th class="text-center">Quantity</th>
                                <th>Unit Price</th>
                                <th>Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>${suppliesHtml}</tbody>
                    </table>
                </div>

                <div class="d-flex gap-2 mt-3">
                    <button onclick="editSupplier(${supplier.id}); document.querySelector('#viewSupplierModal .btn-close').click();" class="btn btn-primary">
                        <i class="fas fa-edit me-2"></i>Edit Supplier
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            `;
            })
            .catch(err => {
                console.error('Error:', err);
                content.innerHTML = '<div class="alert alert-danger">Failed to load supplier details</div>';
            });
    }

    // Submit Form
	const supplierStoreUrl = "{{ route('suppliers.store') }}";
document.getElementById('supplierForm').addEventListener('submit', function (e) {
    e.preventDefault();

    const supplierId = document.getElementById('supplierId').value;
    const formData = new FormData(this);

    let url = supplierStoreUrl;

    // Editing existing supplier
   if (supplierId) {
    url = "{{ url('/supplier') }}/" + supplierId;
    formData.append('_method', 'PUT');
}

    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(async res => {
        const data = await res.json();

        if (!res.ok) {
            throw new Error(data.message || `HTTP ${res.status}`);
        }

        return data;
    })
    .then(data => {
        if (data.status === 'success') {
            alert(data.message);
            location.reload();
        } else {
            alert(data.message || 'Something went wrong');
        }
    })
    .catch(error => {
        console.error(error);
        alert(error.message || 'Server error');
    });
});


    // Delete Supplier
    function deleteSupplier(supplierId) {
    if (!confirm('Delete this supplier?')) return;

    fetch(`/supplier/${supplierId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ _method: 'DELETE' })
    })
    .then(res => res.json())
    .then(data => {
        alert(data.message);
        location.reload();
    })
    .catch(() => alert('Delete failed'));
}


</script>
@endsection
