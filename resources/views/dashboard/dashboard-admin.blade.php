@extends('layouts.main', [
    'title' => 'Dashboard',
    'activePage' => 'dashboard'
    ])

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid force-min-height">

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
                        @if($factory_order)
                            <a class="btn btn-primary btn-sm" href="{{route('factory_order.export')}}">Download Factory Orders</a>
                        @endif
                        @if($europe_vhc)
                            <a class="btn btn-primary btn-sm" href="{{route('europe_vhc_export.export')}}">Download Europe VHC</a>
                        @endif
                        @if($uk_vhc)
                            <a class="btn btn-primary btn-sm" href="{{route('uk_vhc_export.export')}}">Download UK VHC</a>
                        @endif
                        @if($in_stock)
                            <a class="btn btn-primary btn-sm" href="{{route('in_stock_export.export')}}">Download In Stock Orders</a>
                        @endif
                        @if($ready_for_delivery)
                            <a class="btn btn-primary btn-sm" href="{{route('readyfordeliveryexport.export')}}">Download Ready for Delivery</a>
                        @endif
                        @if($delivery_booked)
                            <a class="btn btn-primary btn-sm" href="{{route('deliverybooked.export')}}">Download Delivery Booked</a>
                        @endif
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
            type: 'doughnut',
            data: {
                labels: [
                    "Factory Order - {{ $factory_order }}",
                    "Europe VHC - {{ $europe_vhc }}",
                    "UK VHC - {{ $uk_vhc }}",
                    "In Stock - {{ $in_stock }}",
                    "Ready For Delivery - {{ $ready_for_delivery }}",
                    "Delivery Booked - {{ $delivery_booked }}",
                ],
                datasets: [{
                    backgroundColor: [
                        "#004c6d",
                        "#255e7e",
                        "#3d708f",
                        "#5383a1",
                        "#6996b3",
                        "#7faac6",
                        "#94bed9",
                    ],
                    data: [{{ $factory_order }}, {{ $in_stock }}, {{ $ready_for_delivery }},  {{ $delivery_booked }}, {{ $europe_vhc }}, {{ $uk_vhc }}]
                }]
            },
            options: {
                maintainAspectRatio: false,
                tooltips: {
                    backgroundColor: "rgb(255,255,255)",
                    bodyFontColor: "#858796",
                    borderColor: '#dddfeb',
                    borderWidth: 1,
                    xPadding: 15,
                    yPadding: 15,
                    displayColors: false,
                    caretPadding: 10,
                },
                legend: {
                    display: true,
                    position: 'right',
                    fullWidth: true,
                    labels: {
                        fontSize: 16,
                    },
                },
                cutoutPercentage: 70,
                responsive: true,
            },
        });
    </script>
@endpush
