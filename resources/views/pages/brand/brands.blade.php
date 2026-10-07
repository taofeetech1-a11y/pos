@extends('main_master')
@section('main')

<div class="container-xxl">

    <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
        <div class="flex-grow-1">
            <h4 class="fs-18 fw-semibold m-0">Brand Management</h4>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('brand.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Create new brand
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
                            <h6 class="mb-0 text-muted">Total Brands</h6>
                            <h4 class="mt-2 mb-0">{{ $brands->count() }}</h4>
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
        {{-- <div class="col-md-3">
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
</div> --}}
</div>

<!-- Sales Table -->
<div class="row">
    <div class="col-xl-12">
        <div class="card">

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle" id="salesTable">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($brands as $index => $brand)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <strong class="text-primary">{{ $brand->name }}</strong>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('brand.edit', $brand->id) }}" class="btn btn-sm btn-info" title="Edit Brand">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="{{ route('brand.delete', $brand->id) }}" class="btn btn-sm btn-danger" title="Delete Brand">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="11" class="text-center text-muted py-4">
                                    <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                                    No sales found. <a href="{{ route('brand.create') }}">Create your first brand</a>
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


@endsection
