@extends("main_master")
@section("main")

<div class="container-xxl">

    <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
        <div class="flex-grow-1">
            <h4 class="fs-18 fw-semibold m-0">Supply Chain Analysis</h4>
        </div>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-success" onclick="exportToExcel()">
                <i class="fas fa-file-excel me-2"></i>Export Excel
            </button>
            <a href="{{ route('supplies.index') }}" class="btn btn-primary">
                <i class="fas fa-truck me-2"></i>View All Supplies
            </a>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-3">
        <div class="col-md-2">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="mb-0 text-muted">Total Products</h6>
                            <h4 class="mt-2 mb-0">{{ $totalProducts }}</h4>
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
        <div class="col-md-2">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="mb-0 text-muted">In Stock</h6>
                            <h4 class="mt-2 mb-0 text-success">{{ $inStockCount }}</h4>
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
        <div class="col-md-2">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="mb-0 text-muted">Low Stock</h6>
                            <h4 class="mt-2 mb-0 text-warning">{{ $lowStockCount }}</h4>
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
        <div class="col-md-2">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="mb-0 text-muted">Out of Stock</h6>
                            <h4 class="mt-2 mb-0 text-danger">{{ $outOfStockCount }}</h4>
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
        <div class="col-md-2">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="mb-0 text-muted">Avg Daily Sales</h6>
                            <h4 class="mt-2 mb-0 text-info">{{ number_format($avgDailySales, 0) }}</h4>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-info-subtle text-info rounded fs-3">
                                <i class="fas fa-chart-line"></i>
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
                            <h6 class="mb-0 text-muted">Reorder Soon</h6>
                            <h4 class="mt-2 mb-0 text-warning">{{ $reorderSoonCount }}</h4>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-warning-subtle text-warning rounded fs-3">
                                <i class="fas fa-redo"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-3">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-pie me-2 text-primary"></i>Stock Status Distribution
                    </h5>
                </div>
                <div class="card-body">
                    <div id="stockStatusChart"></div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-bar me-2 text-primary"></i>Top Suppliers by Supply Volume
                    </h5>
                </div>
                <div class="card-body">
                    <div id="topSuppliersChart"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Supply Trends Chart -->
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-area me-2 text-primary"></i>Supply vs Sales Trend (Last 30 Days)
                    </h5>
                </div>
                <div class="card-body">
                    <div id="supplyTrendChart"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stock Velocity Chart -->
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-tachometer-alt me-2 text-primary"></i>Stock Velocity Analysis (Days to Deplete)
                    </h5>
                </div>
                <div class="card-body">
                    <div id="stockVelocityChart"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Products Supply Table -->
    <div class="row">
        <div class="col-xl-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-warehouse me-2 text-primary"></i>Detailed Supply Analysis
                    </h5>
                    <div class="d-flex gap-2">
                        <input type="text" id="searchProduct" class="form-control form-control-sm" style="width: 200px;" placeholder="Search products...">
                        <select id="filterStock" class="form-select form-select-sm" style="width: auto;">
                            <option value="">All Stock Status</option>
                            <option value="in-stock">In Stock</option>
                            <option value="low-stock">Low Stock</option>
                            <option value="out-of-stock">Out of Stock</option>
                            <option value="reorder-soon">Reorder Soon</option>
                        </select>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" id="productsTable">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Product</th>
                                    <th>Current Stock</th>
                                    <th>Stock Alert</th>
                                    <th>Last Supply</th>
                                    <th>Supplier</th>
                                    <th>Avg Daily Sales</th>
                                    <th>Days Left</th>
                                    <th>Reorder Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($products as $index => $product)
                                @php
                                // Get last supply
                                $lastSupply = $product->productSupplies()->latest()->first();

                                // Calculate average daily sales (last 30 days)
                                $salesLast30Days = $product->saleItems()
                                ->whereHas('sale', function($q) {
                                $q->where('created_at', '>=', now()->subDays(30));
                                })
                                ->sum('qty');
                                $avgDailySales = $salesLast30Days / 30;

                                // Calculate days left
                                $daysLeft = $avgDailySales > 0 ? ceil($product->product_qty / $avgDailySales) : 999;

                                // Determine stock status
                                if ($product->product_qty == 0) {
                                $stockStatus = 'out-of-stock';
                                $statusText = 'Out of Stock';
                                $statusColor = 'danger';
                                } elseif ($product->product_qty <= $product->stock_alert) {
                                    $stockStatus = 'low-stock';
                                    $statusText = 'Low Stock';
                                    $statusColor = 'warning';
                                    } elseif ($daysLeft <= 7) { $stockStatus='reorder-soon' ; $statusText='Reorder Soon' ; $statusColor='warning' ; } else { $stockStatus='in-stock' ; $statusText='In Stock' ; $statusColor='success' ; } @endphp <tr data-stock-status="{{ $stockStatus }}">
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($product->images && $product->images->count() > 0)
                                                <img src="{{ asset($product->images->first()->url) }}" class="rounded me-2" width="40" height="40" alt="{{ $product->name }}">
                                                @endif
                                                <div>
                                                    <strong>{{ $product->name }}</strong>
                                                    <div><small class="text-muted">{{ $product->code }}</small></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $statusColor }} fs-6">
                                                {{ $product->product_qty }} units
                                            </span>
                                        </td>
                                        <td>
                                            <span class="text-muted">Alert at: {{ $product->stock_alert }}</span>
                                        </td>
                                        <td>
                                            @if($lastSupply)
                                            <div>
                                                <strong>{{ $lastSupply->qty_supplied }} units</strong>
                                                <div><small class="text-muted">{{ $lastSupply->supply_date->format('d M Y') }}</small></div>
                                            </div>
                                            @else
                                            <span class="text-muted">No supply yet</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($lastSupply && $lastSupply->supplier)
                                            <span class="badge bg-info">{{ $lastSupply->supplier->name }}</span>
                                            @else
                                            <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            <strong class="text-primary">{{ number_format($avgDailySales, 1) }}</strong>
                                            <small class="text-muted">units/day</small>
                                        </td>
                                        <td>
                                            @if($daysLeft <= 7) <span class="badge bg-danger">{{ $daysLeft }} days</span>
                                                @elseif($daysLeft <= 14) <span class="badge bg-warning">{{ $daysLeft }} days</span>
                                                    @elseif($daysLeft < 999) <span class="badge bg-success">{{ $daysLeft }} days</span>
                                                        @else
                                                        <span class="badge bg-secondary">∞</span>
                                                        @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $statusColor }}">
                                                <i class="fas fa-{{ $stockStatus == 'out-of-stock' ? 'times-circle' : ($stockStatus == 'low-stock' ? 'exclamation-triangle' : 'check-circle') }} me-1"></i>
                                                {{ $statusText }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-sm btn-info" onclick="viewSupplyDetails({{ $product->id }})" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-success" onclick="createSupply({{ $product->id }})" title="New Supply">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                        </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="10" class="text-center py-5">
                                                <i class="fas fa-warehouse fa-3x mb-3 d-block text-muted"></i>
                                                No product data available for analysis
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

<!-- Supply Details Modal -->
<div class="modal fade" id="supplyDetailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-warehouse me-2"></i>Supply Chain Details
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

<!-- ApexCharts CDN -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>
    // Chart Data from Backend
    const chartData = @json($chartData);

    // 1. Stock Status Pie Chart
    const stockStatusOptions = {
        series: [chartData.inStock, chartData.lowStock, chartData.outOfStock]
        , chart: {
            type: 'pie'
            , height: 350
        }
        , labels: ['In Stock', 'Low Stock', 'Out of Stock']
        , colors: ['#28a745', '#ffc107', '#dc3545']
        , legend: {
            position: 'bottom'
        }
        , responsive: [{
            breakpoint: 480
            , options: {
                chart: {
                    width: 200
                }
            }
        }]
    };

    const stockStatusChart = new ApexCharts(document.querySelector("#stockStatusChart"), stockStatusOptions);
    stockStatusChart.render();

    // 2. Top Suppliers Bar Chart
    const topSuppliersOptions = {
        series: [{
            name: 'Total Supplied'
            , data: chartData.topSuppliers.map(s => s.total)
        }]
        , chart: {
            type: 'bar'
            , height: 350
        }
        , plotOptions: {
            bar: {
                horizontal: true
                , dataLabels: {
                    position: 'top'
                }
            }
        }
        , dataLabels: {
            enabled: true
            , formatter: function(val) {
                return val + " units";
            }
            , offsetX: -6
            , style: {
                fontSize: '12px'
                , colors: ['#fff']
            }
        }
        , xaxis: {
            categories: chartData.topSuppliers.map(s => s.name)
        }
        , colors: ['#007bff']
    };

    const topSuppliersChart = new ApexCharts(document.querySelector("#topSuppliersChart"), topSuppliersOptions);
    topSuppliersChart.render();

    // 3. Supply vs Sales Trend Chart
    const supplyTrendOptions = {
        series: [{
                name: 'Supplies'
                , data: chartData.supplyTrend.map(t => t.supplied)
            }
            , {
                name: 'Sales'
                , data: chartData.supplyTrend.map(t => t.sold)
            }
        ]
        , chart: {
            type: 'area'
            , height: 350
            , zoom: {
                enabled: true
            }
        }
        , dataLabels: {
            enabled: false
        }
        , stroke: {
            curve: 'smooth'
            , width: 2
        }
        , xaxis: {
            categories: chartData.supplyTrend.map(t => t.date)
        }
        , yaxis: {
            title: {
                text: 'Units'
            }
        }
        , legend: {
            position: 'top'
        }
        , colors: ['#28a745', '#dc3545']
    };

    const supplyTrendChart = new ApexCharts(document.querySelector("#supplyTrendChart"), supplyTrendOptions);
    supplyTrendChart.render();

    // 4. Stock Velocity Chart
    const stockVelocityOptions = {
        series: [{
            name: 'Days to Deplete'
            , data: chartData.stockVelocity.map(p => p.days)
        }]
        , chart: {
            type: 'bar'
            , height: 350
        }
        , plotOptions: {
            bar: {
                distributed: true
                , horizontal: false
                , columnWidth: '55%'
            }
        }
        , dataLabels: {
            enabled: true
            , formatter: function(val) {
                return val === 999 ? '∞' : val + 'd';
            }
        }
        , xaxis: {
            categories: chartData.stockVelocity.map(p => p.name)
            , labels: {
                rotate: -45
                , rotateAlways: true
            }
        }
        , yaxis: {
            title: {
                text: 'Days'
            }
        }
        , colors: chartData.stockVelocity.map(p =>
            p.days <= 7 ? '#dc3545' :
            p.days <= 14 ? '#ffc107' :
            '#28a745'
        )
    };

    const stockVelocityChart = new ApexCharts(document.querySelector("#stockVelocityChart"), stockVelocityOptions);
    stockVelocityChart.render();

    // Search Products
    document.getElementById('searchProduct').addEventListener('input', function() {
        filterProducts();
    });

    // Filter by Stock Status
    document.getElementById('filterStock').addEventListener('change', function() {
        filterProducts();
    });

    function filterProducts() {
        const searchTerm = document.getElementById('searchProduct').value.toLowerCase();
        const stockFilter = document.getElementById('filterStock').value;
        const rows = document.querySelectorAll('#productsTable tbody tr[data-stock-status]');

        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            const rowStatus = row.getAttribute('data-stock-status');

            let showRow = true;

            if (searchTerm && !text.includes(searchTerm)) {
                showRow = false;
            }

            if (stockFilter && rowStatus !== stockFilter) {
                showRow = false;
            }

            row.style.display = showRow ? '' : 'none';
        });
    }

    // View Supply Details
    function viewSupplyDetails(productId) {
        const modal = new bootstrap.Modal(document.getElementById('supplyDetailsModal'));
        const content = document.getElementById('supplyDetailsContent');

        content.innerHTML = `
        <div class="text-center py-5">
            <div class="spinner-border text-primary"></div>
        </div>
    `;

        modal.show();

        fetch(`/supply/analysis/${productId}`)
            .then(res => res.json())
            .then(data => {
                const product = data.product;

                let suppliesHistory = '';
                data.supplyHistory.forEach(supply => {
                    suppliesHistory += `
                    <tr>
                        <td>${supply.date}</td>
                        <td>${supply.supplier}</td>
                        <td>${supply.qty} units</td>
                        <td>₦${supply.unitPrice}</td>
                        <td>₦${supply.total}</td>
                        <td><span class="badge bg-${supply.status === 'approved' ? 'success' : 'warning'}">${supply.status}</span></td>
                    </tr>
                `;
                });

                content.innerHTML = `
                <div class="row">
                    <div class="col-md-4 text-center mb-3">
                        ${product.image
                            ? `<img src="/${product.image}" class="rounded mb-3" width="200" alt="${product.name}">`
                            : `<div class="bg-light rounded p-5 mb-3"><i class="fas fa-box fa-5x text-muted"></i></div>`
                        }
                        <h4>${product.name}</h4>
                        <p class="text-muted">${product.code}</p>
                    </div>
                    <div class="col-md-8">
                        <h5 class="mb-3">Supply Statistics</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="card bg-primary text-white">
                                    <div class="card-body">
                                        <h6>Current Stock</h6>
                                        <h3>${data.currentStock} units</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="card bg-success text-white">
                                    <div class="card-body">
                                        <h6>Total Supplied</h6>
                                        <h3>${data.totalSupplied} units</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="card bg-danger text-white">
                                    <div class="card-body">
                                        <h6>Total Sold</h6>
                                        <h3>${data.totalSold} units</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="card bg-info text-white">
                                    <div class="card-body">
                                        <h6>Avg Daily Sales</h6>
                                        <h3>${data.avgDailySales} units/day</h3>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <h5 class="mb-3 mt-4">Supply History</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm">
                                <thead class="table-light">
                                    <tr>
                                        <th>Date</th>
                                        <th>Supplier</th>
                                        <th>Quantity</th>
                                        <th>Unit Price</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${suppliesHistory || '<tr><td colspan="6" class="text-center">No supply history</td></tr>'}
                                </tbody>
                            </table>
                        </div>

                        <div id="productSupplyTrendChart"></div>
                    </div>
                </div>
            `;

                // Render product-specific trend chart
                if (data.trendData && data.trendData.length > 0) {
                    const trendOptions = {
                        series: [{
                            name: 'Stock Level'
                            , data: data.trendData.map(t => t.stock)
                        }]
                        , chart: {
                            type: 'line'
                            , height: 200
                        }
                        , xaxis: {
                            categories: data.trendData.map(t => t.date)
                        }
                        , colors: ['#007bff']
                        , stroke: {
                            curve: 'smooth'
                            , width: 3
                        }
                    };

                    const trendChart = new ApexCharts(document.querySelector("#productSupplyTrendChart"), trendOptions);
                    trendChart.render();
                }
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

    // Create Supply
    function createSupply(productId) {
        window.location.href = `/supplies?product_id=${productId}`;
    }

    // Export to Excel
    function exportToExcel() {
        window.location.href = '/supply/analysis/export';
    }

</script>
@endsection
