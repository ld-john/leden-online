@extends('layouts.main', [
    'title' => 'Dashboard',
    'activePage' => 'dashboard'
    ])

@section('content')
    <!-- Begin Page Content -->
    <div class="container-xxl">

        <!-- Content Row -->
        <div class="row">
            @include('dashboard.partials.boxes')
        </div>

        <!-- Content Row -->

        <div class="row">

            <!-- Doughnut Chart -->
            <div class="col-lg-6">
                <div class="card shadow mb-4 h-300">
                    <!-- Card Header - Dropdown -->
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between h-75p">
                        <h6 class="m-0 font-weight-bold text-l-blue">Run Report</h6>
                    </div>
                    <!-- Card Body -->
                    <div class="card-body">
                        <div class="chart-area pb-3">
                            <canvas id="runReport"></canvas>
                        </div>
                        <div class="row g-1 row-cols-4">
                            @if($vehicle_statuses['Factory Order'] != 0)
                                <div class="col col-3">
                                    <a class="btn btn-primary w-100 h-100 btn-sm" href="{{route('export.factory_order')}}">Download Factory Orders</a>
                                </div>
                            @endif
                            @if($vehicle_statuses['Europe VHC'] != 0)
                                <div class="col col-3">
                                    <a class="btn btn-primary btn-sm w-100 h-100" href="{{route('export.europe_vhc')}}">Download Europe VHC</a>
                                </div>
                            @endif
                            @if($vehicle_statuses['UK VHC'] != 0)
                                <div class="col col-3">
                                    <a class="btn btn-primary btn-sm w-100 h-100" href="{{route('export.uk_vhc')}}">Download UK VHC</a>
                                </div>
                            @endif
                            @if($vehicle_statuses['In Stock'] != 0)
                                <div class="col col-3">
                                    <a class="btn btn-primary btn-sm w-100 h-100" href="{{route('export.in_stock')}}">Download In Stock Orders</a>
                                </div>
                            @endif
                            @if($vehicle_statuses['In Stock (Registered)'] != 0)
                                <div class="col col-3">
                                    <a class="btn btn-primary btn-sm w-100 h-100" href="{{route('export.in_stock_registered')}}">Download In Stock (Registered) Orders</a>
                                </div>
                            @endif
                            @if($vehicle_statuses['In Stock (Awaiting Dealer Options)'] != 0)
                                <div class="col col-3">
                                    <a class="btn btn-primary btn-sm w-100 h-100" href="{{route('export.in_stock_awaiting_dealer')}}">Download In Stock (Awaiting Dealer Options) Orders</a>
                                </div>
                            @endif
                            @if($vehicle_statuses['Ready for Delivery'] != 0)
                                <div class="col col-3">
                                    <a class="btn btn-primary btn-sm w-100 h-100" href="{{route('export.ready_for_delivery')}}">Download Ready for Delivery</a>
                                </div>
                            @endif
                            @if($vehicle_statuses['Delivery Booked'] != 0)
                                <div class="col col-3">
                                    <a class="btn btn-primary btn-sm w-100 h-100" href="{{route('export.delivery_booked')}}">Download Delivery Booked</a>
                                </div>
                            @endif
                            @if($vehicle_statuses['Awaiting Ship'] != 0)
                                <div class="col col-3">
                                    <a class="btn btn-primary btn-sm w-100 h-100" href="{{route('export.awaiting_ship')}}">Download Awaiting Ship</a>
                                </div>
                            @endif
                            @if($vehicle_statuses['At Converter'] != 0)
                                <div class="col col-3">
                                    <a class="btn btn-primary btn-sm w-100 h-100" href="{{route('export.at_converter')}}">Download At Converter</a>
                                </div>
                            @endif
                            @if($vehicle_statuses['Damaged/Recalled'] != 0)
                                <div class="col col-3">
                                    <a class="btn btn-primary btn-sm w-100 h-100" href="{{route('export.damaged')}}">Download Damaged/Recalled</a>
                                </div>
                            @endif
                            @if($vehicle_statuses['Dealer Transfer'] != 0)
                                <div class="col col-3">
                                    <a class="btn btn-primary btn-sm w-100 h-100" href="{{route('export.dealer_transfer')}}">Download Dealer Transfer</a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <!-- Notifications -->
            @include('dashboard.partials.notifications')
        </div>

        <!-- Content Row -->

        <div class="row">

            <!-- Messages -->
            <div class="col-lg-12">
                @include('dashboard.partials.messages')
            </div>
        </div>

    </div>
    <!-- /.container-fluid -->

@endsection

@push('custom-scripts')
    <script>
        // Pie Chart Example
        let ctx = document.getElementById("runReport");
        let runReport = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: [
                    "Factory Order - {{ $factory_order }}",
                    "Europe VHC - {{ $europe_vhc }}",
                    "UK VHC - {{ $uk_vhc }}",
                    "In Stock - {{ $in_stock }}",
                    "Ready For Delivery - {{ $ready_for_delivery }}",
                    "Delivery Booked - {{ $delivery_booked }}",
                    "Awaiting Ship - {{ $awaiting_ship }}",
                    "At Converter - {{ $at_converter }}",
                    "Damaged - {{ $damaged }}",
                    "Dealer Transfer - {{ $dealer_transfer }}"
                ],
                datasets: [{
                    backgroundColor: [
                        "#004766",
                        "#005980",
                        "#006b99",
                        "#007db3",
                        "#008fcc",
                        "#00a1e6",
                        "#00b3ff",
                        "#1abaff",
                        "#33c2ff"
                    ],
                    data: [{{ $factory_order }}, {{ $europe_vhc }}, {{ $uk_vhc }}, {{ $in_stock }}, {{ $ready_for_delivery }}, {{ $delivery_booked }}, {{ $awaiting_ship }}, {{ $at_converter }}, {{ $damaged }}, {{ $dealer_transfer }} ]
                }]
            },
            options: {
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'right',
                    }
                }
            },
        });
    </script>
@endpush
