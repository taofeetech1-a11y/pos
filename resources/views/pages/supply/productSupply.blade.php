@extends("main_master")
@section("main")

<div class="container-xxl">

    <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
        <div class="flex-grow-1">
            <h4 class="fs-18 fw-semibold m-0">Product Supplies Management</h4>
        </div>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createSupplyModal">
                <i class="fas fa-plus me-2"></i>New Supply
            </button>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-3">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="mb-0 text-muted">Total Supplies</h6>
                            <h4 class="mt-2 mb-0">{{ $supplies->count() }}</h4>
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
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="mb-0 text-muted">Pending Approval</h6>
                            <h4 class="mt-2 mb-0">{{ $supplies->where('status', 'pending')->count() }}</h4>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-warning-subtle text-warning rounded fs-3">
                                <i class="fas fa-clock"></i>
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
                            <h6 class="mb-0 text-muted">Approved</h6>
                            <h4 class="mt-2 mb-0">{{ $supplies->where('status', 'approved')->count() }}</h4>
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
                            <h6 class="mb-0 text-muted">Total Value</h6>
                            <h4 class="mt-2 mb-0">₦{{ number_format($supplies->sum('total_amount'), 2) }}</h4>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-info-subtle text-info rounded fs-3">
                                <i class="fas fa-money-bill-wave"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Supplies Table -->
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">All Supplies</h5>
                    <div class="d-flex gap-2">
                        <input type="date" id="filterDate" class="form-control form-control-sm" style="width: auto;">
                        <select id="filterStatus" class="form-select form-select-sm" style="width: auto;">
                            <option value="">All Status</option>
                            <option value="pending">Pending</option>
                            <option value="approved">Approved</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle" id="suppliesTable">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Invoice No</th>
                                    <th>Date</th>
                                    <th>Product</th>
                                    <th>Supplier</th>
                                    <th>Quantity</th>
                                    <th>Unit Price</th>
                                    <th>Total Amount</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($supplies as $index => $supply)
                                <tr data-date="{{ $supply->supply_date->format('Y-m-d') }}" data-status="{{ $supply->status }}">
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <strong class="text-primary">{{ $supply->invoice_number }}</strong>
                                    </td>
                                    <td>
                                        <div>{{ $supply->supply_date->format('d M Y') }}</div>
                                        <small class="text-muted">{{ $supply->created_at->diffForHumans() }}</small>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($supply->product->images && $supply->product->images->count() > 0)
                                            <img src="{{ asset($supply->product->images->first()->url) }}" class="rounded me-2" width="40" height="40" alt="{{ $supply->product->name }}">
                                            @endif
                                            <div>
                                                <strong>{{ $supply->product->name }}</strong>
                                                <div><small class="text-muted">Code: {{ $supply->product->code }}</small></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $supply->supplier->name }}</td>
                                    <td>
                                        <span class="badge bg-info">{{ $supply->qty_supplied }} units</span>
                                    </td>
                                    <td>₦{{ number_format($supply->unit_price, 2) }}</td>
                                    <td>
                                        <strong class="text-success">₦{{ number_format($supply->total_amount, 2) }}</strong>
                                    </td>
                                    <td>
                                        <span class="badge
                                            @if($supply->status === 'approved') bg-success
                                            @elseif($supply->status === 'pending') bg-warning
                                            @else bg-danger
                                            @endif">
                                            {{ strtoupper($supply->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-sm btn-info" onclick="viewSupply({{ $supply->id }})" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            @if($supply->status === 'pending')
                                            <button type="button" class="btn btn-sm btn-success" onclick="approveSupply({{ $supply->id }})" title="Approve Supply">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger" onclick="cancelSupply({{ $supply->id }})" title="Cancel Supply">
                                                <i class="fas fa-times"></i>
                                            </button>
                                            @elseif($supply->status === 'approved')
                                            <button type="button" class="btn btn-sm btn-warning" onclick="cancelSupply({{ $supply->id }})" title="Reverse Supply">
                                                <i class="fas fa-undo"></i>
                                            </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="10" class="text-center text-muted py-4">
                                        <i class="fas fa-truck fa-3x mb-3 d-block"></i>
                                        No supplies found. <button type="button" class="btn btn-link" data-bs-toggle="modal" data-bs-target="#createSupplyModal">Create your first supply</button>
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

<!-- Create Supply Modal -->
<div class="modal fade" id="createSupplyModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-plus-circle me-2"></i>New Product Supply
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="createSupplyForm">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <!-- Product Selection -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Product <span class="text-danger">*</span></label>
                            <select class="form-select" name="product_id" id="product_id" required>
                                <option value="">Select Product</option>
                                @foreach($products as $product)
                                <option value="{{ $product->id }}" data-name="{{ $product->name }}" data-code="{{ $product->code }}" data-stock="{{ $product->product_qty }}">
                                    {{ $product->name }} ({{ $product->code }})
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Supplier Selection -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Supplier <span class="text-danger">*</span></label>
                            <select class="form-select" name="supplier_id" id="supplier_id" required>
                                <option value="">Select Supplier</option>
                                @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Product Info Display -->
                        <div class="col-12 mb-3" id="productInfo" style="display: none;">
                            <div class="alert alert-info">
                                <strong>Current Stock:</strong> <span id="currentStock">0</span> units
                            </div>
                        </div>

                        <!-- Quantity Supplied -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Quantity Supplied <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="qty_supplied" id="qty_supplied" min="1" placeholder="0" required>
                        </div>

                        <!-- Unit Price -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Unit Price (₦) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="unit_price" id="unit_price" step="0.01" min="0" placeholder="0.00" required>
                        </div>

                        <!-- Total Amount (Auto-calculated) -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Total Amount</label>
                            <input type="text" class="form-control bg-light" id="total_amount_display" readonly value="₦0.00">
                        </div>

                        <!-- Supply Date -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Supply Date</label>
                            <input type="date" class="form-control" name="supply_date" value="{{ date('Y-m-d') }}">
                        </div>

                        <!-- Status -->
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-select" name="status">
                                <option value="pending">Pending Approval</option>
                                <option value="approved">Approve Immediately</option>
                            </select>
                            <small class="text-muted">Approved supplies will immediately update product quantity</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="submitSupplyBtn">
                        <i class="fas fa-save me-2"></i>Create Supply
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Supply Modal -->
<div class="modal fade" id="viewSupplyModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-file-invoice me-2"></i>Supply Details
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="supplyDetailsContent">
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- CSRF TOKEN  --}}
<meta name="csrf-token" content="{{ csrf_token() }}">
<script>
    // Product selection - show current stock
    document.getElementById('product_id').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const stock = selectedOption.getAttribute('data-stock');

        if (this.value) {
            document.getElementById('productInfo').style.display = 'block';
            document.getElementById('currentStock').textContent = stock;
        } else {
            document.getElementById('productInfo').style.display = 'none';
        }
    });

    // Calculate total amount
    function calculateTotal() {
        const qty = parseFloat(document.getElementById('qty_supplied').value) || 0;
        const price = parseFloat(document.getElementById('unit_price').value) || 0;
        const total = qty * price;

        document.getElementById('total_amount_display').value = '₦' + total.toFixed(2);
    }

    document.getElementById('qty_supplied').addEventListener('input', calculateTotal);
    document.getElementById('unit_price').addEventListener('input', calculateTotal);

    // Filter Supplies
    document.getElementById('filterDate').addEventListener('change', filterSupplies);
    document.getElementById('filterStatus').addEventListener('change', filterSupplies);

    function filterSupplies() {
        const date = document.getElementById('filterDate').value;
        const status = document.getElementById('filterStatus').value;
        const rows = document.querySelectorAll('#suppliesTable tbody tr[data-date]');

        let visible = 0;

        rows.forEach(row => {
            const rowDate = row.dataset.date;
            const rowStatus = row.dataset.status;

            let show = true;

            if (date && rowDate !== date) show = false;
            if (status && rowStatus !== status) show = false;

            row.style.display = show ? '' : 'none';
            if (show) visible++;
        });
    }

    // Create Supply
    document.getElementById('createSupplyForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const submitBtn = document.getElementById('submitSupplyBtn');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Processing...';

        const formData = new FormData(this);

        fetch('{{ route("supplies.store") }}', {
                method: 'POST'
                , headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                }
                , body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    alert(data.message);
                    location.reload();
                } else {
                    alert(data.message || 'Failed to create supply!');
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="fas fa-save me-2"></i>Create Supply';
                }
            })
            .catch(err => {
                console.error('Error:', err);
                alert('An error occurred while creating the supply.');
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-save me-2"></i>Create Supply';
            });
    });

    // View Supply Details
    function viewSupply(supplyId) {
        const modal = new bootstrap.Modal(document.getElementById('viewSupplyModal'));
        const content = document.getElementById('supplyDetailsContent');

        content.innerHTML = `
        <div class="text-center py-5">
            <div class="spinner-border text-primary"></div>
        </div>
    `;

        modal.show();

        fetch(`/supplies/${supplyId}/show`)
            .then(res => res.json())
            .then(supply => {
                content.innerHTML = `
                <div class="row">
                    <div class="col-md-6">
                        <h5 class="mb-3">Supply Information</h5>
                        <table class="table table-bordered">
                            <tr>
                                <th width="40%">Invoice Number:</th>
                                <td><strong>${supply.invoice_number}</strong></td>
                            </tr>
                            <tr>
                                <th>Supply Date:</th>
                                <td>${new Date(supply.supply_date).toLocaleDateString()}</td>
                            </tr>
                            <tr>
                                <th>Status:</th>
                                <td>
                                    <span class="badge ${supply.status === 'approved' ? 'bg-success' : supply.status === 'pending' ? 'bg-warning' : 'bg-danger'}">
                                        ${supply.status.toUpperCase()}
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h5 class="mb-3">Product & Supplier</h5>
                        <table class="table table-bordered">
                            <tr>
                                <th width="40%">Product:</th>
                                <td>${supply.product.name}</td>
                            </tr>
                            <tr>
                                <th>Product Code:</th>
                                <td>${supply.product.code}</td>
                            </tr>
                            <tr>
                                <th>Supplier:</th>
                                <td>${supply.supplier.name}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-12 mt-3">
                        <h5 class="mb-3">Financial Details</h5>
                        <table class="table table-bordered">
                            <tr>
                                <th width="30%">Quantity Supplied:</th>
                                <td><strong>${supply.qty_supplied} units</strong></td>
                            </tr>
                            <tr>
                                <th>Unit Price:</th>
                                <td>₦${parseFloat(supply.unit_price).toFixed(2)}</td>
                            </tr>
                            <tr class="table-success">
                                <th>Total Amount:</th>
                                <td><strong>₦${parseFloat(supply.total_amount).toFixed(2)}</strong></td>
                            </tr>
                        </table>
                    </div>
                </div>
            `;
            })
            .catch(err => {
                console.error('Error:', err);
                content.innerHTML = `
                <div class="alert alert-danger">
                    Failed to load supply details
                </div>
            `;
            });
    }

    // Approve Supply
    function approveSupply(supplyId) {
        if (!confirm('Are you sure you want to approve this supply? Product quantity will be updated.')) {
            return;
        }

        const btn = event.target.closest('button');
        const originalHtml = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

        fetch(`/supplies/${supplyId}/approve`, {
                method: 'POST'
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
                    alert(data.message || 'Failed to approve supply!');
                    btn.disabled = false;
                    btn.innerHTML = originalHtml;
                }
            })
            .catch(err => {
                console.error('Error:', err);
                alert('An error occurred.');
                btn.disabled = false;
                btn.innerHTML = originalHtml;
            });
    }

    // Cancel Supply
    function cancelSupply(supplyId) {
        if (!confirm('Are you sure you want to cancel this supply? If approved, product quantity will be decreased.')) {
            return;
        }

        const btn = event.target.closest('button');
        const originalHtml = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

        fetch(`/supplies/${supplyId}/cancel`, {
                method: 'POST'
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
                    alert(data.message || 'Failed to cancel supply!');
                    btn.disabled = false;
                    btn.innerHTML = originalHtml;
                }
            })
            .catch(err => {
                console.error('Error:', err);
                alert('An error occurred.');
                btn.disabled = false;
                btn.innerHTML = originalHtml;
            });
    }

</script>
@endsection
