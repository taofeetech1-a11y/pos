@extends("main_master")
@section("main")

<div class="container-xxl">

    <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
        <div class="flex-grow-1">
            <h4 class="fs-18 fw-semibold m-0">Sales Management</h4>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('pos.index') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>New Sale
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
                            <h6 class="mb-0 text-muted">Total Sales</h6>
                            <h4 class="mt-2 mb-0">{{ $sales->count() }}</h4>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-primary-subtle text-primary rounded fs-3">
                                <i class="fas fa-shopping-cart"></i>
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
                            <h6 class="mb-0 text-muted">Total Revenue</h6>
                            <h4 class="mt-2 mb-0">₦{{ number_format($sales->sum('grand_total'), 2) }}</h4>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-success-subtle text-success rounded fs-3">
                                <i class="fas fa-money-bill-wave"></i>
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
                            <h6 class="mb-0 text-muted">Paid Sales</h6>
                            <h4 class="mt-2 mb-0">{{ $sales->where('payment_status', 'paid')->count() }}</h4>
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
                            <h6 class="mb-0 text-muted">Pending Sales</h6>
                            <h4 class="mt-2 mb-0">{{ $sales->where('payment_status', 'pending')->count() }}</h4>
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
    </div>

    <!-- Sales Table -->
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">All Sales</h5>
                    <div class="d-flex gap-2">
                        <input type="date" id="filterDate" class="form-control form-control-sm" style="width: auto;">
                        <select id="filterStatus" class="form-select form-select-sm" style="width: auto;">
                            <option value="">All Status</option>
                            <option value="paid">Paid</option>
                            <option value="pending">Pending</option>
                            <option value="refunded">Refunded</option>
                        </select>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle" id="salesTable">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Receipt No</th>
                                    <th>Date</th>
                                    <th>Cashier</th>
                                    <th>Items</th>
                                    <th>Sub Total</th>
                                    <th>Discount</th>
                                    <th>Grand Total</th>
                                    <th>Payment</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($sales as $index => $sale)
                                <tr data-date="{{ $sale->created_at->format('Y-m-d') }}" data-status="{{ $sale->payment_status }}">
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <strong class="text-primary">{{ $sale->receipt_number }}</strong>
                                    </td>
                                    <td>
                                        <div>{{ $sale->created_at->format('d M Y') }}</div>
                                        <small class="text-muted">{{ $sale->created_at->format('h:i A') }}</small>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm me-2">
                                                <span class="avatar-title bg-info-subtle text-info rounded-circle">
                                                    {{ substr($sale->cashier->name ?? 'U', 0, 1) }}
                                                </span>
                                            </div>
                                            <div>{{ $sale->cashier->name ?? 'N/A' }}</div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">
                                            {{ $sale->saleItems->count() }} item(s)
                                        </span>
                                    </td>
                                    <td>₦{{ number_format($sale->sub_total, 2) }}</td>
                                    <td>
                                        @if($sale->discount > 0)
                                        <span class="text-danger">
                                            -₦{{ number_format($sale->discount_type === 'percentage' ? ($sale->sub_total * $sale->discount) / 100 : $sale->discount, 2) }}
                                        </span>
                                        @if($sale->discount_type === 'percentage')
                                        <small class="text-muted">({{ $sale->discount }}%)</small>
                                        @endif
                                        @else
                                        <span class="text-muted">₦0.00</span>
                                        @endif
                                    </td>
                                    <td>
                                        <strong class="text-success">₦{{ number_format($sale->grand_total, 2) }}</strong>
                                    </td>
                                    <td>
                                        <span class="badge
                                            @if($sale->payment_method === 'cash') bg-success
                                            @elseif($sale->payment_method === 'pos') bg-primary
                                            @else bg-info
                                            @endif">
                                            {{ strtoupper($sale->payment_method) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge
                                            @if($sale->payment_status === 'paid') bg-success
                                            @elseif($sale->payment_status === 'pending') bg-warning
                                            @else bg-danger
                                            @endif">
                                            {{ strtoupper($sale->payment_status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-sm btn-info" onclick="viewSaleDetails({{ $sale->id }})" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <a href="{{ route('pos.receipt', $sale->receipt_number) }}" class="btn btn-sm btn-primary" target="_blank" title="Print Receipt">
                                                <i class="fas fa-print"></i>
                                            </a>
                                            @if($sale->payment_status !== 'refunded')
                                            <button type="button" class="btn btn-sm btn-danger" onclick="refundSale({{ $sale->id }})" title="Refund Sale">
                                                <i class="fas fa-undo"></i>
                                            </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="11" class="text-center text-muted py-4">
                                        <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                                        No sales found. <a href="{{ route('pos.index') }}">Create your first sale</a>
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

<!-- Sale Details Modal -->
<div class="modal fade" id="saleDetailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-receipt me-2"></i>Sale Details
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="saleDetailsContent">
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<meta name="csrf-token" content="{{ csrf_token() }}">

<script>
    // Filter by Date
    document.getElementById('filterDate').addEventListener('change', filterSales);
    document.getElementById('filterStatus').addEventListener('change', filterSales);

    function filterSales() {
        const date = document.getElementById('filterDate').value;
        const status = document.getElementById('filterStatus').value;
        const rows = document.querySelectorAll('#salesTable tbody tr[data-date]');
        const emptyRow = document.getElementById('emptyRow');

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

        if (emptyRow) {
            emptyRow.style.display = visible === 0 ? '' : 'none';
        }
    }

    // View Sale Details
    function viewSaleDetails(id) {

        const modalEl = document.getElementById('saleDetailsModal');
        const modal = new bootstrap.Modal(modalEl);
        const content = document.getElementById('saleDetailsContent');

        content.innerHTML = `
        <div class="text-center py-5">
            <div class="spinner-border text-primary"></div>
        </div>
    `;

        modal.show();

        fetch(`/sales/${id}`)
            .then(res => res.json())
            .then(sale => {

                let items = '';

                sale.sale_items.forEach(item => {
                    items += `
                    <tr>
                        <td>${item.product?.name ?? 'N/A'}</td>
                        <td class="text-center">${item.qty}</td>
                        <td class="text-end">₦${Number(item.unit_price).toFixed(2)}</td>
                        <td class="text-end fw-bold">₦${Number(item.total_price).toFixed(2)}</td>
                    </tr>
                `;
                });

                content.innerHTML = `
                <p><strong>Receipt:</strong> ${sale.receipt_number}</p>
                <p><strong>Cashier:</strong> ${sale.cashier?.name ?? 'N/A'}</p>
                <p><strong>Date:</strong> ${new Date(sale.created_at).toLocaleString()}</p>

                <table class="table table-bordered mt-3">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th class="text-center">Qty</th>
                            <th class="text-end">Unit</th>
                            <th class="text-end">Total</th>
                        </tr>
                    </thead>
                    <tbody>${items}</tbody>
                </table>

                <div class="text-end fw-bold">
                    Grand Total: ₦${Number(sale.grand_total).toFixed(2)}
                </div>
            `;
            })
            .catch(() => {
                content.innerHTML = `
                <div class="alert alert-danger">
                    Failed to load sale details
                </div>
            `;
            });
    }

    // Refund Sale
    function refundSale(saleId) {
        if (!confirm('Are you sure you want to refund this sale? Product quantities will be restored.')) {
            return;
        }

        const btn = event.target.closest('button');
        const originalHtml = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

        fetch('/sales/refund', {
                method: 'POST'
                , headers: {
                    'Content-Type': 'application/json'
                    , 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
                , body: JSON.stringify({
                    sale_id: saleId
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    alert(data.message);
                    location.reload();
                } else {
                    alert(data.message || 'Refund failed!');
                    btn.disabled = false;
                    btn.innerHTML = originalHtml;
                }
            })
            .catch(err => {
                console.error('Error:', err);
                alert('An error occurred while processing the refund.');
                btn.disabled = false;
                btn.innerHTML = originalHtml;
            });
    }

</script>
@endsection
