@extends('main_master')
@section('main')

<div class="container-xxl">

    <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
        <div class="flex-grow-1">
            <h4 class="fs-18 fw-semibold m-0">Brand Performance Analysis</h4>
        </div>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-success" onclick="exportToExcel()">
                <i class="fas fa-file-excel me-2"></i>Export Excel
            </button>
            <a href="{{ route('brands') }}" class="btn btn-primary">
                <i class="fas fa-tags me-2"></i>Manage Brands
            </a>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-3">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="mb-0 text-muted">Total Brands</h6>
                            <h4 class="mt-2 mb-0">{{ $totalBrands }}</h4>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-primary-subtle text-primary rounded fs-3">
                                <i class="fas fa-tags"></i>
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
                            <h6 class="mb-0 text-muted">Total Products</h6>
                            <h4 class="mt-2 mb-0">{{ $totalProducts }}</h4>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-info-subtle text-info rounded fs-3">
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
                            <h6 class="mb-0 text-muted">Total Revenue</h6>
                            <h4 class="mt-2 mb-0">₦{{ number_format($totalRevenueAll, 2) }}</h4>
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
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="mb-0 text-muted">Top Brand</h6>
                            <h4 class="mt-2 mb-0">{{ $topBrandName }}</h4>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-warning-subtle text-warning rounded fs-3">
                                <i class="fas fa-crown"></i>
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
                        <i class="fas fa-chart-pie me-2 text-primary"></i>Brand Market Share by Revenue
                    </h5>
                </div>
                <div class="card-body">
                    <div id="marketShareChart"></div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-bar me-2 text-primary"></i>Top Brands by Sales Volume
                    </h5>
                </div>
                <div class="card-body">
                    <div id="salesVolumeChart"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Revenue & Profit Chart -->
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-line me-2 text-primary"></i>Brand Revenue vs Profit Comparison
                    </h5>
                </div>
                <div class="card-body">
                    <div id="revenueProfitChart"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Performance Trend Chart -->
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-area me-2 text-primary"></i>Brand Performance Trend (Last 30 Days)
                    </h5>
                </div>
                <div class="card-body">
                    <div id="performanceTrendChart"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Brands Analysis Table -->
    <div class="row">
        <div class="col-xl-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-analytics me-2 text-primary"></i>Detailed Brand Analysis
                    </h5>
                    <div class="d-flex gap-2">
                        <input type="text" id="searchBrand" class="form-control form-control-sm" style="width: 200px;" placeholder="Search brands...">
                        <select id="sortBy" class="form-select form-select-sm" style="width: auto;">
                            <option value="">Sort By</option>
                            <option value="revenue">Revenue (High to Low)</option>
                            <option value="products">Products Count</option>
                            <option value="profit">Profit Margin</option>
                            <option value="sales">Sales Volume</option>
                        </select>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" id="brandsTable">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Brand Name</th>
                                    <th>Products</th>
                                    <th>Total Sales</th>
                                    <th>Revenue</th>
                                    <th>Avg Cost</th>
                                    <th>Profit</th>
                                    <th>Profit Margin</th>
                                    <th>Market Share</th>
                                    <th>Performance</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($brands as $index => $brand)
                                @php
                                $productsCount = $brand->products->count();

                                // Calculate total sales volume
                                $totalSales = 0;
                                foreach ($brand->products as $product) {
                                $totalSales += $product->saleItems->sum('qty');
                                }

                                // Calculate total revenue
                                $totalRevenue = 0;
                                foreach ($brand->products as $product) {
                                $totalRevenue += $product->saleItems->sum('total_price');
                                }

                                // Calculate average cost
                                $totalCost = 0;
                                $costCount = 0;
                                foreach ($brand->products as $product) {
                                $avgCost = $product->productSupplies->avg('unit_price') ?? 0;
                                if ($avgCost > 0) {
                                $totalCost += $avgCost;
                                $costCount++;
                                }
                                }
                                $avgCost = $costCount > 0 ? $totalCost / $costCount : 0;

                                // Calculate profit
                                $totalProfit = 0;
                                foreach ($brand->products as $product) {
                                $productCost = $product->productSupplies->avg('unit_price') ?? 0;
                                $productPrice = $product->price;
                                $productSales = $product->saleItems->sum('qty');
                                $totalProfit += ($productPrice - $productCost) * $productSales;
                                }

                                // Calculate profit margin
                                $profitMargin = $totalRevenue > 0 ? (($totalProfit / $totalRevenue) * 100) : 0;

                                // Calculate market share
                                $marketShare = $totalRevenueAll > 0 ? (($totalRevenue / $totalRevenueAll) * 100) : 0;

                                // Determine performance
                                if ($profitMargin >= 30) {
                                $performance = 'excellent';
                                $performanceText = 'Excellent';
                                $performanceColor = 'success';
                                } elseif ($profitMargin >= 20) {
                                $performance = 'good';
                                $performanceText = 'Good';
                                $performanceColor = 'info';
                                } elseif ($profitMargin >= 10) {
                                $performance = 'average';
                                $performanceText = 'Average';
                                $performanceColor = 'warning';
                                } else {
                                $performance = 'poor';
                                $performanceText = 'Poor';
                                $performanceColor = 'danger';
                                }
                                @endphp
                                <tr data-revenue="{{ $totalRevenue }}" data-products="{{ $productsCount }}" data-profit="{{ $profitMargin }}" data-sales="{{ $totalSales }}">
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-primary-subtle text-primary rounded-circle me-2 d-flex align-items-center justify-content-center">
                                                <strong>{{ strtoupper(substr($brand->name, 0, 2)) }}</strong>
                                            </div>
                                            <div>
                                                <strong>{{ $brand->name }}</strong>
                                                @if($brand->note)
                                                <div><small class="text-muted">{{ Str::limit($brand->note, 30) }}</small></div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-info fs-6">{{ $productsCount }} products</span>
                                    </td>
                                    <td>
                                        <strong class="text-primary">{{ number_format($totalSales) }} units</strong>
                                    </td>
                                    <td>
                                        <strong class="text-success">₦{{ number_format($totalRevenueAll, 2) }}</strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">₦{{ number_format($avgCost, 2) }}</span>
                                    </td>
                                    <td>
                                        <strong class="text-{{ $totalProfit >= 0 ? 'success' : 'danger' }}">
                                            {{ $totalProfit >= 0 ? '+' : '' }}₦{{ number_format($totalProfit, 2) }}
                                        </strong>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="badge bg-{{ $profitMargin >= 20 ? 'success' : ($profitMargin >= 10 ? 'warning' : 'danger') }} me-2">
                                                {{ number_format($profitMargin, 1) }}%
                                            </span>
                                            <div class="progress" style="width: 50px; height: 8px;">
                                                <div class="progress-bar bg-{{ $profitMargin >= 20 ? 'success' : ($profitMargin >= 10 ? 'warning' : 'danger') }}" style="width: {{ min($profitMargin, 100) }}%"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="badge bg-primary me-2">{{ number_format($marketShare, 1) }}%</span>
                                            <div class="progress" style="width: 50px; height: 8px;">
                                                <div class="progress-bar bg-primary" style="width: {{ $marketShare }}%"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $performanceColor }}">
                                            <i class="fas fa-{{ $performance == 'excellent' ? 'star' : ($performance == 'good' ? 'thumbs-up' : ($performance == 'average' ? 'minus' : 'exclamation-triangle')) }} me-1"></i>
                                            {{ $performanceText }}
                                        </span>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-info" onclick="viewBrandDetails({{ $brand->id }})" title="View Details">
                                            <i class="fas fa-chart-line"></i>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="11" class="text-center py-5">
                                        <i class="fas fa-tags fa-3x mb-3 d-block text-muted"></i>
                                        No brand data available for analysis
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

<!-- Brand Details Modal -->
<div class="modal fade" id="brandDetailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-chart-line me-2"></i>Brand Performance Details
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="brandDetailsContent">
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

    // 1. Market Share Pie Chart
    const marketShareOptions = {
        series: chartData.marketShare.map(b => parseFloat(b.revenue))
        , chart: {
            type: 'donut'
            , height: 350
        }
        , labels: chartData.marketShare.map(b => b.name)
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
        , plotOptions: {
            pie: {
                donut: {
                    labels: {
                        show: true
                        , total: {
                            show: true
                            , label: 'Total Revenue'
                            , formatter: function(w) {
                                return '₦' + w.globals.seriesTotals.reduce((a, b) => a + b, 0).toFixed(2);
                            }
                        }
                    }
                }
            }
        }
    };

    const marketShareChart = new ApexCharts(document.querySelector("#marketShareChart"), marketShareOptions);
    marketShareChart.render();

    // 2. Sales Volume Bar Chart
    const salesVolumeOptions = {
        series: [{
            name: 'Units Sold'
            , data: chartData.salesVolume.map(b => b.sales)
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
            categories: chartData.salesVolume.map(b => b.name)
        }
        , colors: ['#007bff']
    };

    const salesVolumeChart = new ApexCharts(document.querySelector("#salesVolumeChart"), salesVolumeOptions);
    salesVolumeChart.render();

    // 3. Revenue vs Profit Chart
    const revenueProfitOptions = {
        series: [{
                name: 'Revenue'
                , data: chartData.revenueProfit.map(b => parseFloat(b.revenue))
            }
            , {
                name: 'Profit'
                , data: chartData.revenueProfit.map(b => parseFloat(b.profit))
            }
        ]
        , chart: {
            type: 'bar'
            , height: 350
        }
        , plotOptions: {
            bar: {
                horizontal: false
                , columnWidth: '55%'
            }
        }
        , dataLabels: {
            enabled: false
        }
        , stroke: {
            show: true
            , width: 2
            , colors: ['transparent']
        }
        , xaxis: {
            categories: chartData.revenueProfit.map(b => b.name)
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
        , fill: {
            opacity: 1
        }
        , tooltip: {
            y: {
                formatter: function(val) {
                    return "₦" + val.toFixed(2);
                }
            }
        }
        , colors: ['#007bff', '#28a745']
    };

    const revenueProfitChart = new ApexCharts(document.querySelector("#revenueProfitChart"), revenueProfitOptions);
    revenueProfitChart.render();

    // 4. Performance Trend Chart
    const performanceTrendOptions = {
        series: chartData.performanceTrend.map(brand => ({
            name: brand.name
            , data: brand.data
        }))
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
            categories: chartData.trendDates
        }
        , yaxis: {
            title: {
                text: 'Sales Volume'
            }
        }
        , legend: {
            position: 'top'
        }
        , fill: {
            type: 'gradient'
            , gradient: {
                shadeIntensity: 1
                , opacityFrom: 0.7
                , opacityTo: 0.3
            }
        }
    };

    const performanceTrendChart = new ApexCharts(document.querySelector("#performanceTrendChart"), performanceTrendOptions);
    performanceTrendChart.render();

    // Search Brands
    document.getElementById('searchBrand').addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const rows = document.querySelectorAll('#brandsTable tbody tr');

        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchTerm) ? '' : 'none';
        });
    });

    // Sort By
    document.getElementById('sortBy').addEventListener('change', function() {
        const sortBy = this.value;
        if (!sortBy) return;

        const tbody = document.querySelector('#brandsTable tbody');
        const rows = Array.from(tbody.querySelectorAll('tr[data-revenue]'));

        rows.sort((a, b) => {
            const aVal = parseFloat(a.getAttribute(`data-${sortBy}`));
            const bVal = parseFloat(b.getAttribute(`data-${sortBy}`));
            return bVal - aVal;
        });

        rows.forEach(row => tbody.appendChild(row));
    });

    // View Brand Details
    function viewBrandDetails(brandId) {
        const modal = new bootstrap.Modal(document.getElementById('brandDetailsModal'));
        const content = document.getElementById('brandDetailsContent');

        content.innerHTML = `
        <div class="text-center py-5">
            <div class="spinner-border text-primary"></div>
        </div>
    `;

        modal.show();

        fetch(`/brand/brands/analysis/${brandId}`)
            .then(res => res.json())
            .then(data => {
                const brand = data.brand;

                let productsHtml = '';
                data.products.forEach(product => {
                    productsHtml += `
                    <tr>
                        <td>${product.name}</td>
                        <td>${product.sales} units</td>
                        <td>₦${product.revenue}</td>
                        <td>₦${product.profit}</td>
                        <td><span class="badge bg-${product.profitMargin >= 20 ? 'success' : 'warning'}">${product.profitMargin}%</span></td>
                    </tr>
                `;
                });

                content.innerHTML = `
                <div class="row">
                    <div class="col-md-4">
                        <div class="card bg-primary text-white mb-3">
                            <div class="card-body text-center">
                                <h1>${brand.name}</h1>
                                <p class="mb-0">${brand.note || 'No description'}</p>
                            </div>
                        </div>

                        <div class="card mb-3">
                            <div class="card-body">
                                <h6>Performance Summary</h6>
                                <hr>
                                <p><strong>Products:</strong> ${data.productsCount}</p>
                                <p><strong>Total Sales:</strong> ${data.totalSales} units</p>
                                <p><strong>Revenue:</strong> ₦${data.totalRevenue}</p>
                                <p><strong>Profit:</strong> ₦${data.totalProfit}</p>
                                <p><strong>Margin:</strong> <span class="badge bg-${data.profitMargin >= 20 ? 'success' : 'warning'}">${data.profitMargin}%</span></p>
                                <p><strong>Market Share:</strong> ${data.marketShare}%</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <h5 class="mb-3">Products Performance</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm">
                                <thead class="table-light">
                                    <tr>
                                        <th>Product</th>
                                        <th>Sales</th>
                                        <th>Revenue</th>
                                        <th>Profit</th>
                                        <th>Margin</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${productsHtml || '<tr><td colspan="5" class="text-center">No products</td></tr>'}
                                </tbody>
                            </table>
                        </div>

                        <h5 class="mb-3 mt-4">Sales Trend (Last 30 Days)</h5>
                        <div id="brandTrendChart"></div>
                    </div>
                </div>
            `;

                // Render brand-specific trend chart
                if (data.trendData && data.trendData.length > 0) {
                    const trendOptions = {
                        series: [{
                            name: 'Sales'
                            , data: data.trendData.map(t => t.sales)
                        }]
                        , chart: {
                            type: 'area'
                            , height: 250
                        }
                        , xaxis: {
                            categories: data.trendData.map(t => t.date)
                        }
                        , colors: ['#007bff']
                        , stroke: {
                            curve: 'smooth'
                            , width: 2
                        }
                        , fill: {
                            type: 'gradient'
                            , gradient: {
                                shadeIntensity: 1
                                , opacityFrom: 0.7
                                , opacityTo: 0.3
                            }
                        }
                    };

                    const trendChart = new ApexCharts(document.querySelector("#brandTrendChart"), trendOptions);
                    trendChart.render();
                }
            })
            .catch(err => {
                console.error('Error:', err);
                content.innerHTML = `
                <div class="alert alert-danger">
                    Failed to load brand details
                </div>
            `;
            });
    }

    // Export to Excel
    function exportToExcel() {
        window.location.href = '/brand/brands/analysis/export';
    }

</script>
@endsection
