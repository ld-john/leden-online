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
                <div class="card shadow mb-4">
                    <!-- Card Header - Dropdown -->
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-l-blue">Run Report</h6>
                    </div>
                    <!-- Card Body -->
                    <div class="card-body">
                        <div class="chart-area">
                            <canvas id="runReport"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bar Chart -->
            <div class="col-lg-6">
                <div class="card shadow mb-4">
                    <!-- Card Header - Dropdown -->
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-l-blue">Vehicles Registered <small>(Last 6 months)</small></h6>
                    </div>
                    <!-- Card Body -->
                    <div class="card-body">
                        <div class="chart-area">
                            <canvas id="vehicle_stock_chart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Content Row -->

        <div class="row">

            <!-- Notifications -->
            @include('dashboard.partials.notifications')

            <!-- Messages -->
            @include('dashboard.partials.messages')

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
                    "In Stock - {{ $in_stock }}",
                    "Orders Placed - {{ $orders_placed }}",
                    "Ready For Delivery - {{ $ready_for_delivery }}",
                    "Factory Order - {{ $factory_order }}",
                    "Delivery Booked - {{ $delivered }}",
                    "Europe VHC - {{ $europe_vhc }}",
                    "UK VHC - {{ $uk_vhc }}"
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
                    data: [{{ $in_stock }}, {{ $orders_placed }}, {{ $ready_for_delivery }}, {{ $factory_order }}, {{ $delivered }}, {{ $europe_vhc }}, {{ $uk_vhc }}]
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

        let vehicle_stock = document.getElementById('vehicle_stock_chart');
        let vehicle_stock_chart = new Chart(vehicle_stock, {
            type: 'bar',
            data: {
                labels: [@foreach ($vehicles_registered as $month) "{{ $month->month_label }}", @endforeach],
                datasets: [{
                    label: "Vehicles",
                    backgroundColor: "#3d708f",
                    hoverBackgroundColor: "#004c6d",
                    borderColor: "#3d708f",
                    data: [@foreach ($vehicles_registered as $order) {{ $order->data }}, @endforeach],
                }],
            },
            options: {
                maintainAspectRatio: false,
                layout: {
                    padding: {
                        left: 10,
                        right: 25,
                        top: 25,
                        bottom: 0
                    }
                },
                scales: {
                    xAxes: [{
                        time: {
                            unit: 'month'
                        },
                        gridLines: {
                            display: false,
                            drawBorder: false
                        },
                        ticks: {
                            maxTicksLimit: 6
                        },
                        maxBarThickness: 25,
                    }],
                    yAxes: [{
                        ticks: {
                            min: 0,
                            max: {{ $max }},
                        },
                        gridLines: {
                            color: "rgb(234, 236, 244)",
                            zeroLineColor: "rgb(234, 236, 244)",
                            drawBorder: false,
                            borderDash: [2],
                            zeroLineBorderDash: [2]
                        }
                    }],
                },
                legend: {
                    display: false
                },
                tooltips: {
                    titleMarginBottom: 10,
                    titleFontColor: '#6e707e',
                    titleFontSize: 14,
                    backgroundColor: "rgb(255,255,255)",
                    bodyFontColor: "#858796",
                    borderColor: '#dddfeb',
                    borderWidth: 1,
                    xPadding: 15,
                    yPadding: 15,
                    displayColors: false,
                    caretPadding: 10,
                    callbacks: {
                        label: function (tooltipItem, chart) {
                            let datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
                            return tooltipItem.yLabel + ' vehicles';
                        }
                    }
                },
            }
        });
    </script>
@endpush
