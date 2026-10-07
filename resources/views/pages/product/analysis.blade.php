@extends("main_master")
@section("main")

<div class="container-xxl">

    <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
        <div class="flex-grow-1">
            <h4 class="fs-18 fw-semibold m-0">Product Analysis</h4>
        </div>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-success" onclick="exportToExcel()">
                <i class="fas fa-file-excel me-2"></i>Export Excel
            </button>
            <button type="button" class="btn btn-primary" onclick="window.print()">
                <i class="fas fa-print me-2"></i>Print Report
            </button>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-3">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
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
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="mb-0 text-muted">Profitable Products</h6>
                            <h4 class="mt-2 mb-0 text-success">{{ $profitableCount }}</h4>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-success-subtle text-success rounded fs-3">
                                <i class="fas fa-arrow-up"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="mb-0 text-muted">Loss Products</h6>
                            <h4 class="mt-2 mb-0 text-danger">{{ $lossCount }}</h4>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-danger-subtle text-danger rounded fs-3">
                                <i class="fas fa-arrow-down"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="mb-0 text-muted">Total Profit Margin</h6>
                            <h4 class="mt-2 mb-0 text-success">{{ number_format($totalProfitMargin, 2) }}%</h4>
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
    </div>

    <!-- Charts Row -->
    <div class="row mb-3">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-pie me-2 text-primary"></i>Profit vs Loss Distribution
                    </h5>
                </div>
                <div class="card-body">
                    <div id="profitLossChart"></div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-bar me-2 text-primary"></i>Top 10 Products by Profit Margin
                    </h5>
                </div>
                <div class="card-body">
                    <div id="topProductsChart"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Chart -->
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-line me-2 text-primary"></i>Product Performance Overview
                    </h5>
                </div>
                <div class="card-body">
                    <div id="performanceChart"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Products Table -->
    <div class="row">
        <div class="col-xl-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fa-solid fa-chart-bar me-2 text-primary"></i>Detailed Product Analysis
                    </h5>
                    <div class="d-flex gap-2">
                        <input type="text" id="searchProduct" class="form-control form-control-sm" style="width: 200px;" placeholder="Search products...">
                        <select id="filterProfitability" class="form-select form-select-sm" style="width: auto;">
                            <option value="">All Products</option>
                            <option value="profit">Profitable Only</option>
                            <option value="loss">Loss Only</option>
                            <option value="breakeven">Break Even</option>
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
                                    <th>Cost Price</th>
                                    <th>Selling Price</th>
                                    <th>Profit/Loss</th>
                                    <th>Margin %</th>
                                    <th>Total Sold</th>
                                    <th>Total Revenue</th>
                                    <th>Total Profit</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($products as $index => $product)
                                @php
                                // Calculate average cost price from supplies
                                $avgCostPrice = $product->productSupplies->avg('unit_price') ?? 0;
                                $sellingPrice = $product->price;

                                // Calculate profit/loss per unit
                                $profitLoss = $sellingPrice - $avgCostPrice;

                                // Calculate margin percentage
                                $marginPercentage = $avgCostPrice > 0 ? (($profitLoss / $avgCostPrice) * 100) : 0;

                                // Get total sold from sales
                                $totalSold = $product->saleItems->sum('qty') ?? 0;

                                // Calculate total revenue
                                $totalRevenue = $product->saleItems->sum('total_price') ?? 0;

                                // Calculate total profit
                                $totalProfit = $totalSold * $profitLoss;

                                // Determine status
                                if ($profitLoss > 0) {
                                $status = 'profit';
                                $statusText = 'Profitable';
                                $statusColor = 'success';
                                } elseif ($profitLoss < 0) { $status='loss' ; $statusText='Loss' ; $statusColor='danger' ; } else { $status='breakeven' ; $statusText='Break Even' ; $statusColor='warning' ; } @endphp <tr data-status="{{ $status }}">
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
                                        <span class="badge bg-secondary">₦{{ number_format($avgCostPrice, 2) }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">₦{{ number_format($sellingPrice, 2) }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $profitLoss >= 0 ? 'success' : 'danger' }}">
                                            {{ $profitLoss >= 0 ? '+' : '' }}₦{{ number_format($profitLoss, 2) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="badge bg-{{ $marginPercentage >= 0 ? 'success' : 'danger' }} me-2">
                                                {{ number_format($marginPercentage, 2) }}%
                                            </span>
                                            <div class="progress" style="width: 60px; height: 8px;">
                                                <div class="progress-bar bg-{{ $marginPercentage >= 0 ? 'success' : 'danger' }}" style="width: {{ min(abs($marginPercentage), 100) }}%"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $totalSold }} units</span>
                                    </td>
                                    <td>
                                        <strong class="text-primary">₦{{ number_format($totalRevenue, 2) }}</strong>
                                    </td>
                                    <td>
                                        <strong class="text-{{ $totalProfit >= 0 ? 'success' : 'danger' }}">
                                            {{ $totalProfit >= 0 ? '+' : '' }}₦{{ number_format($totalProfit, 2) }}
                                        </strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $statusColor }}">
                                            <i class="fas fa-{{ $profitLoss > 0 ? 'arrow-up' : ($profitLoss < 0 ? 'arrow-down' : 'minus') }} me-1"></i>
                                            {{ $statusText }}
                                        </span>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-info" onclick="viewProductAnalysis({{ $product->id }})" title="View Details">
                                            <i class="fas fa-chart-line"></i>
                                        </button>
                                    </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="11" class="text-center py-5">
                                            <i class="fas fa-chart-bar fa-3x mb-3 d-block text-muted"></i>
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

<!-- Product Analysis Detail Modal -->
<div class="modal fade" id="productAnalysisModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-chart-line me-2"></i>Detailed Product Analysis
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="productAnalysisContent">
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
    // Chart Data
    const chartData = @json($chartData);

    // 1. Profit vs Loss Pie Chart
    const profitLossOptions = {
        series: [chartData.profitableCount, chartData.lossCount, chartData.breakEvenCount]
        , chart: {
            type: 'donut'
            , height: 350
        }
        , labels: ['Profitable', 'Loss', 'Break Even']
        , colors: ['#28a745', '#dc3545', '#ffc107']
        , legend: {
            position: 'bottom'
        }
        , responsive: [{
            breakpoint: 480
            , options: {
                chart: {
                    width: 200
                }
                , legend: {
                    position: 'bottom'
                }
            }
        }]
    };

    const profitLossChart = new ApexCharts(document.querySelector("#profitLossChart"), profitLossOptions);
    profitLossChart.render();

    // 2. Top 10 Products Bar Chart
    const topProductsOptions = {
        series: [{
            name: 'Profit Margin %'
            , data: chartData.topProducts.map(p => p.margin)
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
                return val.toFixed(2) + "%";
            }
            , offsetX: -6
            , style: {
                fontSize: '12px'
                , colors: ['#fff']
            }
        }
        , xaxis: {
            categories: chartData.topProducts.map(p => p.name)
            , title: {
                text: 'Profit Margin %'
            }
        }
        , colors: ['#28a745']
    };

    const topProductsChart = new ApexCharts(document.querySelector("#topProductsChart"), topProductsOptions);
    topProductsChart.render();

    // 3. Performance Overview Chart
    const performanceOptions = {
        series: [{
                name: 'Selling Price'
                , data: chartData.allProducts.map(p => p.sellingPrice)
            }
            , {
                name: 'Cost Price'
                , data: chartData.allProducts.map(p => p.costPrice)
            }
            , {
                name: 'Profit'
                , data: chartData.allProducts.map(p => p.profit)
            }
        ]
        , chart: {
            type: 'line'
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
            categories: chartData.allProducts.map(p => p.name)
            , labels: {
                rotate: -45
                , rotateAlways: true
            }
        }
        , yaxis: {
            title: {
                text: 'Amount (₦)'
            }
        }
        , legend: {
            position: 'top'
        }
        , colors: ['#007bff', '#ffc107', '#28a745']
    };

    const performanceChart = new ApexCharts(document.querySelector("#performanceChart"), performanceOptions);
    performanceChart.render();

    // Search Products
    document.getElementById('searchProduct').addEventListener('input', function() {
        filterProducts();
    });

    // Filter by Profitability
    document.getElementById('filterProfitability').addEventListener('change', function() {
        filterProducts();
    });

    function filterProducts() {
        const searchTerm = document.getElementById('searchProduct').value.toLowerCase();
        const statusFilter = document.getElementById('filterProfitability').value;
        const rows = document.querySelectorAll('#productsTable tbody tr[data-status]');

        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            const rowStatus = row.getAttribute('data-status');

            let showRow = true;

            if (searchTerm && !text.includes(searchTerm)) {
                showRow = false;
            }

            if (statusFilter && rowStatus !== statusFilter) {
                showRow = false;
            }

            row.style.display = showRow ? '' : 'none';
        });
    }

    // View Product Analysis
    function viewProductAnalysis(productId) {
        const modal = new bootstrap.Modal(document.getElementById('productAnalysisModal'));
        const content = document.getElementById('productAnalysisContent');

        content.innerHTML = `
        <div class="text-center py-5">
            <div class="spinner-border text-primary"></div>
        </div>
    `;

        modal.show();

        fetch(`/product/products/${productId}/analysis`)
            .then(res => res.json())
            .then(data => {
                const product = data.product;

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
                        <h5 class="mb-3">Financial Analysis</h5>
                        <table class="table table-bordered">
                            <tr>
                                <th width="40%">Average Cost Price:</th>
                                <td class="text-end"><strong>₦${parseFloat(data.avgCostPrice).toFixed(2)}</strong></td>
                            </tr>
                            <tr>
                                <th>Selling Price:</th>
                                <td class="text-end"><strong>₦${parseFloat(data.sellingPrice).toFixed(2)}</strong></td>
                            </tr>
                            <tr class="${data.profitLoss >= 0 ? 'table-success' : 'table-danger'}">
                                <th>Profit/Loss per Unit:</th>
                                <td class="text-end"><strong>${data.profitLoss >= 0 ? '+' : ''}₦${parseFloat(data.profitLoss).toFixed(2)}</strong></td>
                            </tr>
                            <tr>
                                <th>Profit Margin:</th>
                                <td class="text-end"><strong>${parseFloat(data.marginPercentage).toFixed(2)}%</strong></td>
                            </tr>
                            <tr>
                                <th>Total Units Sold:</th>
                                <td class="text-end"><strong>${data.totalSold} units</strong></td>
                            </tr>
                            <tr>
                                <th>Total Revenue:</th>
                                <td class="text-end"><strong class="text-primary">₦${parseFloat(data.totalRevenue).toFixed(2)}</strong></td>
                            </tr>
                            <tr class="${data.totalProfit >= 0 ? 'table-success' : 'table-danger'}">
                                <th>Total Profit/Loss:</th>
                                <td class="text-end"><strong>${data.totalProfit >= 0 ? '+' : ''}₦${parseFloat(data.totalProfit).toFixed(2)}</strong></td>
                            </tr>
                        </table>

                        <div class="alert ${data.profitLoss >= 0 ? 'alert-success' : 'alert-danger'}">
                            <h6 class="mb-2"><i class="fas fa-info-circle me-2"></i>Analysis Summary:</h6>
                            ${data.profitLoss >= 0
                                ? `This product is <strong>profitable</strong> with a margin of ${parseFloat(data.marginPercentage).toFixed(2)}%. Keep this price point!`
                                : `This product is making a <strong>loss</strong>. Consider increasing the selling price or reducing cost.`
                            }
                        </div>

                        <div id="productTrendChart"></div>
                    </div>
                </div>
            `;

                // Render trend chart in modal
                if (data.salesTrend && data.salesTrend.length > 0) {
                    const trendOptions = {
                        series: [{
                            name: 'Units Sold'
                            , data: data.salesTrend.map(t => t.qty)
                        }]
                        , chart: {
                            type: 'area'
                            , height: 200
                        }
                        , xaxis: {
                            categories: data.salesTrend.map(t => t.date)
                        }
                        , colors: ['#007bff']
                    };

                    const trendChart = new ApexCharts(document.querySelector("#productTrendChart"), trendOptions);
                    trendChart.render();
                }
            })
            .catch(err => {
                console.error('Error:', err);
                content.innerHTML = `
                <div class="alert alert-danger">
                    Failed to load product analysis
                </div>
            `;
            });
    }

    // Export to Excel
    function exportToExcel() {
        window.location.href = '/product/products/analysis/export';
    }

    // Print styles
    window.addEventListener('beforeprint', function() {
        document.querySelectorAll('.btn, .form-control, .form-select').forEach(el => {
            el.style.display = 'none';
        });
    });

    window.addEventListener('afterprint', function() {
        document.querySelectorAll('.btn, .form-control, .form-select').forEach(el => {
            el.style.display = '';
        });
    });

</script>
@endsection
