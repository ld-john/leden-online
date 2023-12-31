
@if(Auth::user()->role === 'broker')
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-l-blue shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col me-2">
                        <div class="text-xs font-weight-bold text-l-blue text-uppercase mb-1">Live Orders</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $live_orders }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-boxes fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-l-blue shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col me-2">
                        <div class="text-xs font-weight-bold text-l-blue text-uppercase mb-1">Downloads</div>
                        <div class="row no-gutters align-items-center justify-center">
                            <div class="col-auto">
                                <a href="{{ route('export.brokers_orders_export', Auth::user()->company_id) }}" class="btn btn-success text-white">Orders</a>
                            </div>
                            <div class="col-auto">
                                <a href="{{ route('export.brokers_stock_export', Auth::user()->company_id) }}" class="btn btn-success text-white">Stock List</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fa-solid fa-download fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-l-blue shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col me-2">
                        <div class="text-xs font-weight-bold text-l-blue text-uppercase mb-1">In Stock Orders</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $vehicle_statuses['In Stock'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-receipt fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-l-blue shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col me-2">
                        <div class="text-xs font-weight-bold text-l-blue text-uppercase mb-1">Completed Orders</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $vehicle_statuses['Completed Orders'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clipboard-check fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

@else
    <div class="col-xl-4 mb-4">
        <div class="card border-left-l-blue shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col me-2">
                        <div class="text-xs font-weight-bold text-l-blue text-uppercase mb-1">Live Orders</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $live_orders }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-boxes fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-l-blue shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col me-2">
                        <div class="text-xs font-weight-bold text-l-blue text-uppercase mb-1">In Stock Orders</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $vehicle_statuses['In Stock'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-receipt fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-l-blue shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col me-2">
                        <div class="text-xs font-weight-bold text-l-blue text-uppercase mb-1">Completed Orders</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $vehicle_statuses['Completed Orders'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clipboard-check fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
