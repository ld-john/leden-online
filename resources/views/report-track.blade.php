@extends('layouts.main', [
    'title' => 'Report & Track',
    'activePage' => 'report-track'
    ])

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid force-min-height">

        <!-- Content Row -->

        <div class="row">

            <!-- Doughnut Chart -->
            <div class="col-lg-12">
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

        </div>

        <!-- Content Row -->

        <div class="row">


            <!-- Weekly Sales -->
            <div class="col-lg-4">
                <div class="card shadow mb-4">
                    <!-- Card Header - Dropdown -->
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-l-blue">Weekly <small>(Created - Last 6 weeks)</small></h6>
                    </div>
                    <!-- Card Body -->
                    <div class="card-body">
                        <div class="chart-area">
                            <canvas id="weeklySales"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Monthly Sales -->
            <div class="col-lg-4">
                <div class="card shadow mb-4">
                    <!-- Card Header - Dropdown -->
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-l-blue">Monthly <small>(Created - Last 6 months)</small>
                        </h6>
                    </div>
                    <!-- Card Body -->
                    <div class="card-body">
                        <div class="chart-area">
                            <canvas id="monthSales"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quarterly Sales -->
            <div class="col-lg-4">
                <div class="card shadow mb-4">
                    <!-- Card Header - Dropdown -->
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-l-blue">Quarterly <small>(Created - Last 4 Quarters)</small></h6>
                    </div>
                    <!-- Card Body -->
                    <div class="card-body">
                        <div class="chart-area">
                            <canvas id="quarterlySales"></canvas>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="row">
            <div class="col">
                <a href="{{route('reporting_download')}}" class="btn btn-primary">Download Report</a>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->

@endsection

@push('custom-scripts')
    <script>
        // Pie Chart Example
        var ctx = document.getElementById("runReport");
        var runReport = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: [
                    "Orders Place - {{ $orders_placed }}",
                    "Ready For Delivery - {{ $ready_for_delivery }}",
                    "Factory Order - {{ $factory_order }}",
                    "Delivery Booked - {{ $delivered }}",
                    "Completed - {{ $completed_orders }}",
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
                    data: [{{ $orders_placed }}, {{ $ready_for_delivery }}, {{ $factory_order }}, {{ $delivered }}, {{ $completed_orders }}, {{ $europe_vhc }}, {{ $uk_vhc }}]
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

        var month_sales = document.getElementById('monthSales');
        var month_sales_chart = new Chart(month_sales, {
            type: 'bar',
            data: {
                labels: [@foreach ($monthly_sales as $mon_month) "{{ $mon_month->month_label }}", @endforeach],
                datasets: [{
                    label: "Vehicles",
                    backgroundColor: "#0b61be",
                    hoverBackgroundColor: "#02356b",
                    borderColor: "#0b61be",
                    data: [@foreach ($monthly_sales as $mon_order) {{ $mon_order->data }}, @endforeach],
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
                            max: {{ $month_max }},
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
                            var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
                            return tooltipItem.yLabel + ' vehicles';
                        }
                    }
                },
            }
        });

        var weekly_sales = document.getElementById('weeklySales');
        var weekly_sales_chart = new Chart(weekly_sales, {
            type: 'bar',
            data: {
                labels: [@foreach ($weekly_sales as $week) "{{$week->label}}", @endforeach],
                datasets: [{
                    label: "Vehicles",
                    backgroundColor: "#00b43d",
                    hoverBackgroundColor: "#007a29",
                    borderColor: "#00b43d",
                    data: [@foreach ($weekly_sales as $week_order) {{ $week_order->data }}, @endforeach],
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
                            unit: 'week'
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
                            max: {{ $weekly_max }},
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
                            var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
                            return tooltipItem.yLabel + ' vehicles';
                        }
                    }
                },
            }
        });

        var quarterly_sales = document.getElementById('quarterlySales');
        var quarterly_sales_chart = new Chart(quarterly_sales, {
            type: 'bar',
            data: {
                labels: [@foreach ($quarterly_sales as $quarter) "Q{{ $quarter->quarter }} {{ $quarter->year }}", @endforeach],
                datasets: [{
                    label: "Vehicles",
                    backgroundColor: "#691883",
                    hoverBackgroundColor: "#4a095e",
                    borderColor: "#691883",
                    data: [@foreach ($quarterly_sales as $quarter_order) {{ $quarter_order->data }}, @endforeach],
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
                            unit: 'quarter'
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
                            max: {{ $quarterly_max }},
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
                            var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
                            return tooltipItem.yLabel + ' vehicles';
                        }
                    }
                },
            }
        });
    </script>
@endpush
