@extends('layouts.main', [
    'title' => 'Report & Track',
    'activePage' => 'report-track'
    ])

@section('content')

    <!-- Begin Page Content -->
    <div class="container-fluid force-min-height">

        <!-- Content Row -->

        <div class="row">


            <!-- Weekly Placed -->
            <div class="col-lg-12">
                <div class="card shadow mb-4">
                    <!-- Card Header - Dropdown -->
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-l-blue">Weekly <small>(Orders Placed - Last 6 weeks)</small></h6>
                    </div>
                    <!-- Card Body -->
                    <div class="card-body">
                        <div class="chart-area" style="position: relative; height: 40vh;">
                            <canvas id="weeklySales"></canvas>
                        </div>
                        <h5>Orders Placed</h5>
                        @foreach($weekly_sales as $weekly)
                            @if($weekly['data'] === 0)
                                @continue
                            @endif
                            <a
                                    class="btn btn-primary btn-sm"
                                    href="/reporting/weekly-placed/{{$weekly['year']}}/{{$weekly['week']}}"
                            >
                                {{$weekly['label']}}
                            </a>
                        @endforeach
                        <h5>Vehicles Registered</h5>
                        @foreach($weekly_registered as $weekly)
                            @if($weekly['data'] === 0)
                                @continue
                            @endif
                            <a class="btn btn-primary btn-sm" href="/reporting/weekly-registered/{{$weekly['year']}}/{{$weekly['week']}}">{{$weekly['label']}}</a>
                        @endforeach
                        <h5>Orders Completed</h5>
                        @foreach($weekly_completed as $weekly)
                            @if($weekly['data'] === 0)
                                @continue
                            @endif
                            <a
                                    class="btn btn-primary btn-sm"
                                    href="/reporting/weekly-completed/{{$weekly['year']}}/{{$weekly['week']}}"
                            >
                                {{$weekly['label']}}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Monthly Placed -->
            <div class="col-lg-12">
                <div class="card shadow mb-4">
                    <!-- Card Header - Dropdown -->
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-l-blue">Monthly <small>(Orders Placed - Last 6 months)</small>
                        </h6>
                    </div>
                    <!-- Card Body -->
                    <div class="card-body">
                        <div class="chart-area" style="position: relative; height: 40vh;">
                            <canvas id="monthSales"></canvas>
                        </div>
                        <h5>Orders Placed</h5>
                        @foreach($monthly_sales as $monthly_sale)
                            @if($monthly_sale['data'] === 0)
                                @continue
                            @endif
                            <a href="/reporting/monthly-placed/{{$monthly_sale['year']}}/{{$monthly_sale['month']}}" class="btn btn-primary btn-sm">{{$monthly_sale['month_label']}} {{$monthly_sale['year']}}</a>
                        @endforeach
                        <h5>Vehicles Registered</h5>
                        @foreach($monthly_registered as $monthly)
                            @if($monthly['data'] === 0)
                                @continue
                            @endif
                            <a href="/reporting/monthly-registered/{{$monthly['year']}}/{{$monthly['month']}}" class="btn btn-primary btn-sm">{{$monthly['month_label']}} {{$monthly['year']}}</a>
                        @endforeach
                        <h5>Orders Completed</h5>
                        @foreach($monthly_completed as $monthly)
                            @if($monthly['data'] === 0)
                                @continue
                            @endif
                            <a href="/reporting/monthly-completed/{{$monthly['year']}}/{{$monthly['month']}}" class="btn btn-primary btn-sm">{{$monthly['month_label']}} {{$monthly['year']}}</a>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Quarterly Placed -->
            <div class="col-lg-12">
                <div class="card shadow mb-4">
                    <!-- Card Header - Dropdown -->
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-l-blue">Quarterly <small>(Orders Placed - Last 4 Quarters)</small></h6>
                    </div>
                    <!-- Card Body -->
                    <div class="card-body">
                        <div class="chart-area" style="position: relative; height: 40vh;">
                            <canvas id="quarterlySales"></canvas>
                        </div>
                        <h5>Orders Placed</h5>
                        @foreach($quarterly_sales as $quarterly)
                            @if($quarterly['data'] === 0)
                                @continue
                            @endif
                            <a class="btn btn-primary btn-sm"
                               href="/reporting/quarterly-placed/{{$quarterly['year']}}/{{$quarterly['quarter']}}"
                            >
                                {{$quarterly['label']}}
                            </a>
                        @endforeach
                        <h5>Vehicles Registered</h5>
                        @foreach($quarterly_registered as $quarterly)
                            @if($quarterly['data'] === 0)
                                @continue
                            @endif
                            <a
                                    class="btn btn-primary btn-sm"
                                    href="/reporting/quarterly-registered/{{$quarterly['year']}}/{{$quarterly['quarter']}}"
                            >
                                {{$quarterly['label']}}
                            </a>
                        @endforeach
                        <h5>Orders Completed</h5>
                        @foreach($quarterly_completed as $quarterly)
                            @if($quarterly['data'] === 0)
                                @continue
                            @endif
                            <a
                                    class="btn btn-primary btn-sm"
                                    href="/reporting/quarterly-completed/{{$quarterly['year']}}/{{$quarterly['quarter']}}"
                            >
                                {{$quarterly['label']}}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        {{--        <div class="row">--}}


        {{--            <!-- Weekly Registered -->--}}
        {{--            <div class="col-lg-4">--}}
        {{--                <div class="card shadow mb-4">--}}
        {{--                    <!-- Card Header - Dropdown -->--}}
        {{--                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">--}}
        {{--                        <h6 class="m-0 font-weight-bold text-l-blue">Weekly <small>(Registered Vehicles - Last 6 weeks)</small></h6>--}}
        {{--                    </div>--}}
        {{--                    <!-- Card Body -->--}}
        {{--                    <div class="card-body">--}}
        {{--                        <div class="chart-area">--}}
        {{--                            <canvas id="weeklyRegistered"></canvas>--}}
        {{--                        </div>--}}

        {{--                    </div>--}}
        {{--                </div>--}}
        {{--            </div>--}}

        {{--            <!-- Monthly Registered -->--}}
        {{--            <div class="col-lg-4">--}}
        {{--                <div class="card shadow mb-4">--}}
        {{--                    <!-- Card Header - Dropdown -->--}}
        {{--                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">--}}
        {{--                        <h6 class="m-0 font-weight-bold text-l-blue">Monthly <small>(Registered Vehicles - Last 6 months)</small>--}}
        {{--                        </h6>--}}
        {{--                    </div>--}}
        {{--                    <!-- Card Body -->--}}
        {{--                    <div class="card-body">--}}
        {{--                        <div class="chart-area">--}}
        {{--                            <canvas id="monthRegistered" style="height: 400px"></canvas>--}}
        {{--                        </div>--}}

        {{--                        @foreach($monthly_registered as $monthly)--}}
        {{--                            <a href="/reporting/monthly-registered/{{$monthly['year']}}/{{$monthly['month']}}" class="btn btn-primary btn-sm">{{$monthly['month_label']}} {{$monthly['year']}}</a>--}}
        {{--                        @endforeach--}}
        {{--                    </div>--}}
        {{--                </div>--}}
        {{--            </div>--}}

        {{--            <!-- Quarterly Registered -->--}}
        {{--            <div class="col-lg-4">--}}
        {{--                <div class="card shadow mb-4">--}}
        {{--                    <!-- Card Header - Dropdown -->--}}
        {{--                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">--}}
        {{--                        <h6 class="m-0 font-weight-bold text-l-blue">Quarterly <small>(Registered Vehicles - Last 4 Quarters)</small></h6>--}}
        {{--                    </div>--}}
        {{--                    <!-- Card Body -->--}}
        {{--                    <div class="card-body">--}}
        {{--                        <div class="chart-area">--}}
        {{--                            <canvas id="quarterlyRegistered"></canvas>--}}
        {{--                        </div>--}}


        {{--                    </div>--}}
        {{--                </div>--}}
        {{--            </div>--}}
        {{--        </div>--}}
        {{--        <div class="row">--}}

        {{--            <!-- Weekly Completed -->--}}
        {{--            <div class="col-lg-4">--}}
        {{--                <div class="card shadow mb-4">--}}
        {{--                    <!-- Card Header - Dropdown -->--}}
        {{--                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">--}}
        {{--                        <h6 class="m-0 font-weight-bold text-l-blue">Weekly <small>(Completed Vehicles - Last 6 weeks)</small></h6>--}}
        {{--                    </div>--}}
        {{--                    <!-- Card Body -->--}}
        {{--                    <div class="card-body">--}}
        {{--                        <div class="chart-area">--}}
        {{--                            <canvas id="weeklyCompleted"></canvas>--}}
        {{--                        </div>--}}



        {{--                    </div>--}}
        {{--                </div>--}}
        {{--            </div>--}}

        {{--            <!-- Monthly Completed -->--}}
        {{--            <div class="col-lg-4">--}}
        {{--                <div class="card shadow mb-4">--}}
        {{--                    <!-- Card Header - Dropdown -->--}}
        {{--                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">--}}
        {{--                        <h6 class="m-0 font-weight-bold text-l-blue">Monthly <small>(Completed Vehicles - Last 6 months)</small>--}}
        {{--                        </h6>--}}
        {{--                    </div>--}}
        {{--                    <!-- Card Body -->--}}
        {{--                    <div class="card-body">--}}
        {{--                        <div class="chart-area">--}}
        {{--                            <canvas id="monthCompleted"></canvas>--}}
        {{--                        </div>--}}

        {{--                        @foreach($monthly_completed as $monthly)--}}
        {{--                            <a href="/reporting/monthly-completed/{{$monthly['year']}}/{{$monthly['month']}}" class="btn btn-primary btn-sm">{{$monthly['month_label']}} {{$monthly['year']}}</a>--}}
        {{--                        @endforeach--}}
        {{--                    </div>--}}
        {{--                </div>--}}
        {{--            </div>--}}

        {{--            <!-- Quarterly Completed -->--}}
        {{--            <div class="col-lg-4">--}}
        {{--                <div class="card shadow mb-4">--}}
        {{--                    <!-- Card Header - Dropdown -->--}}
        {{--                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">--}}
        {{--                        <h6 class="m-0 font-weight-bold text-l-blue">Quarterly <small>(Completed Vehicles - Last 4 Quarters)</small></h6>--}}
        {{--                    </div>--}}
        {{--                    <!-- Card Body -->--}}
        {{--                    <div class="card-body">--}}
        {{--                        <div class="chart-area">--}}
        {{--                            <canvas id="quarterlyCompleted"></canvas>--}}
        {{--                        </div>--}}


        {{--                    </div>--}}
        {{--                </div>--}}
        {{--            </div>--}}
        {{--        </div>--}}
    </div>
    <!-- /.container-fluid -->

@endsection

@push('custom-scripts')
    <script>
        // Pie Chart Example
        var ctx = document.getElementById("runReport");
        var runReport = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: [
                    "Orders Placed - {{ $orders_placed }}",
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
                plugins: {
                    legend: {
                        display: true,
                        position: 'right',
                    }
                }
            },
        });

        var month_sales = document.getElementById('monthSales');
        var month_sales_chart = new Chart(month_sales, {
            type: 'bar',
            data: {
                labels: [@foreach ($monthly_sales as $mon_month) "{{ $mon_month['month_label'] }}", @endforeach],
                datasets: [
                    {
                        label: "Orders Placed",
                        backgroundColor: "#E0C44D",
                        hoverBackgroundColor: "#947F23",
                        borderColor: "#E0C44D",
                        data: [@foreach ($monthly_sales as $mon_order) {{ $mon_order['data'] }}, @endforeach],
                    },
                    {
                        label: "Vehicles Registered",
                        backgroundColor: "#00b43d",
                        hoverBackgroundColor: "#007a29",
                        borderColor: "#00b43d",
                        data: [@foreach ($monthly_registered as $mon_order) {{ $mon_order['data'] }}, @endforeach],
                    },
                    {
                        label: "Orders Completed",
                        backgroundColor: "#0b61be",
                        hoverBackgroundColor: "#02356b",
                        borderColor: "#0b61be",
                        data: [@foreach ($monthly_completed as $mon_order) {{ $mon_order['data'] }}, @endforeach],
                    }
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'right',
                    }
                }
            },
        });

        {{--var month_registered = document.getElementById('monthRegistered');--}}
        {{--var month_registered_chart = new Chart(month_registered, {--}}
        {{--    type: 'bar',--}}
        {{--    data: {--}}
        {{--        labels: [@foreach ($monthly_registered as $mon_month) "{{ $mon_month['month_label'] }}", @endforeach],--}}
        {{--        datasets: [{--}}
        {{--            label: "Vehicles",--}}
        {{--            backgroundColor: "#0b61be",--}}
        {{--            hoverBackgroundColor: "#02356b",--}}
        {{--            borderColor: "#0b61be",--}}
        {{--            data: [@foreach ($monthly_registered as $mon_order) {{ $mon_order['data'] }}, @endforeach],--}}
        {{--        }],--}}
        {{--    },--}}
        {{--    options: {--}}
        {{--        scales: {--}}
        {{--            y: {--}}
        {{--                beginAtZero: true,--}}
        {{--            }--}}
        {{--        },--}}
        {{--        plugins: {--}}
        {{--            legend: {--}}
        {{--                display: false--}}
        {{--            }--}}
        {{--        }--}}
        {{--    }--}}
        {{--});--}}

        {{--var month_completed = document.getElementById('monthCompleted');--}}
        {{--var month_completed_chart = new Chart(month_completed, {--}}
        {{--    type: 'bar',--}}
        {{--    data: {--}}
        {{--        labels: [@foreach ($monthly_completed as $mon_month) "{{ $mon_month['month_label'] }}", @endforeach],--}}
        {{--        datasets: [{--}}
        {{--            label: "Vehicles",--}}
        {{--            backgroundColor: "#0b61be",--}}
        {{--            hoverBackgroundColor: "#02356b",--}}
        {{--            borderColor: "#0b61be",--}}
        {{--            data: [@foreach ($monthly_completed as $mon_order) {{ $mon_order['data'] }}, @endforeach],--}}
        {{--        }],--}}
        {{--    },--}}
        {{--    options: {--}}
        {{--        scales: {--}}
        {{--            y: {--}}
        {{--                beginAtZero: true,--}}
        {{--            }--}}
        {{--        },--}}
        {{--        plugins: {--}}
        {{--            legend: {--}}
        {{--                display: false--}}
        {{--            }--}}
        {{--        }--}}
        {{--    }--}}
        {{--});--}}

        let weekly_sales = document.getElementById('weeklySales');
        let weekly_sales_chart = new Chart(weekly_sales, {
            type: 'bar',
            data: {
                labels: [@foreach ($weekly_sales as $week) "{{$week['label']}}", @endforeach],
                datasets: [{
                    label: "Orders Placed",
                    backgroundColor: "#E0C44D",
                    hoverBackgroundColor: "#947F23",
                    borderColor: "#E0C44D",
                    data: [@foreach ($weekly_sales as $week_order) {{ $week_order['data'] }}, @endforeach],
                },
                    {
                        label: "Vehicles Registered",
                        backgroundColor: "#00b43d",
                        hoverBackgroundColor: "#007a29",
                        borderColor: "#00b43d",
                        data: [@foreach ($weekly_registered as $week_order) {{ $week_order['data'] }}, @endforeach],
                    },
                    {
                        label: "Orders Completed",
                        backgroundColor: "#0b61be",
                        hoverBackgroundColor: "#02356b",
                        borderColor: "#0b61be",
                        data: [@foreach ($weekly_completed as $week_order) {{ $week_order['data'] }}, @endforeach],
                    }
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'right',
                    }
                }
            },
        });

        {{--var weekly_completed = document.getElementById('weeklyCompleted');--}}
        {{--var weekly_completed_chart = new Chart(weekly_completed, {--}}
        {{--    type: 'bar',--}}
        {{--    data: {--}}
        {{--        labels: [@foreach ($weekly_completed as $week) "{{$week['label']}}", @endforeach],--}}
        {{--        datasets: [{--}}
        {{--            label: "Vehicles",--}}
        {{--            backgroundColor: "#00b43d",--}}
        {{--            hoverBackgroundColor: "#007a29",--}}
        {{--            borderColor: "#00b43d",--}}
        {{--            data: [@foreach ($weekly_completed as $week_order) {{ $week_order['data'] }}, @endforeach],--}}
        {{--        }],--}}
        {{--    },--}}
        {{--    options: {--}}
        {{--        scales: {--}}
        {{--            y: {--}}
        {{--                beginAtZero: true,--}}
        {{--            }--}}
        {{--        },--}}
        {{--        plugins: {--}}
        {{--            legend: {--}}
        {{--                display: false--}}
        {{--            }--}}
        {{--        }--}}
        {{--    },--}}
        {{--});--}}

        {{--var weekly_registered = document.getElementById('weeklyRegistered');--}}
        {{--var weekly_registered_chart = new Chart(weekly_registered, {--}}
        {{--    type: 'bar',--}}
        {{--    data: {--}}
        {{--        labels: [@foreach ($weekly_registered as $week) "{{$week['label']}}", @endforeach],--}}
        {{--        datasets: [{--}}
        {{--            label: "Vehicles",--}}
        {{--            backgroundColor: "#00b43d",--}}
        {{--            hoverBackgroundColor: "#007a29",--}}
        {{--            borderColor: "#00b43d",--}}
        {{--            data: [@foreach ($weekly_registered as $week_order) {{ $week_order['data'] }}, @endforeach],--}}
        {{--        }],--}}
        {{--    },--}}
        {{--    options: {--}}
        {{--        scales: {--}}
        {{--            y: {--}}
        {{--                beginAtZero: true,--}}
        {{--            }--}}
        {{--        },--}}
        {{--        plugins: {--}}
        {{--            legend: {--}}
        {{--                display: false--}}
        {{--            }--}}
        {{--        }--}}
        {{--    },--}}
        {{--});--}}

        var quarterly_sales = document.getElementById('quarterlySales');
        var quarterly_sales_chart = new Chart(quarterly_sales, {
            type: 'bar',
            data: {
                labels: [@foreach ($quarterly_sales as $quarter) "{{$quarter['label']}}", @endforeach],
                datasets: [{
                    label: "Orders Placed",
                    backgroundColor: "#E0C44D",
                    hoverBackgroundColor: "#947F23",
                    borderColor: "#E0C44D",
                    data: [@foreach ($quarterly_sales as $quarter_order) {{ $quarter_order['data'] }}, @endforeach],
                },
                    {
                        label: "Registered Vehicles",
                        backgroundColor: "#00b43d",
                        hoverBackgroundColor: "#007a29",
                        borderColor: "#00b43d",
                        data: [@foreach ($quarterly_registered as $quarter_order) {{ $quarter_order['data'] }}, @endforeach]
                    },
                    {
                        label: "Completed Orders",
                        backgroundColor: "#0b61be",
                        hoverBackgroundColor: "#02356b",
                        borderColor: "#0b61be",
                        data: [@foreach ($quarterly_completed as $quarter_order) {{ $quarter_order['data'] }}, @endforeach]
                    }
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'right',
                    }
                }
            },
        });

        {{--var quarterly_registered = document.getElementById('quarterlyRegistered');--}}
        {{--var quarterly_registered_chart = new Chart(quarterly_registered, {--}}
        {{--    type: 'bar',--}}
        {{--    data: {--}}
        {{--        labels: [@foreach ($quarterly_registered as $quarter) "Q{{ $quarter->quarter }} {{ $quarter->year }}", @endforeach],--}}
        {{--        datasets: [{--}}
        {{--            label: "Vehicles",--}}
        {{--            backgroundColor: "#691883",--}}
        {{--            hoverBackgroundColor: "#4a095e",--}}
        {{--            borderColor: "#691883",--}}
        {{--            data: [@foreach ($quarterly_registered as $quarter_order) {{ $quarter_order->data }}, @endforeach],--}}
        {{--        }],--}}
        {{--    },--}}
        {{--    options: {--}}
        {{--        scales: {--}}
        {{--            y: {--}}
        {{--                beginAtZero: true,--}}
        {{--            }--}}
        {{--        },--}}
        {{--        plugins: {--}}
        {{--            legend: {--}}
        {{--                display: false--}}
        {{--            }--}}
        {{--        }--}}
        {{--    },--}}
        {{--});--}}

        {{--var quarterly_completed = document.getElementById('quarterlyCompleted');--}}
        {{--var quarterly_completed_chart = new Chart(quarterly_completed, {--}}
        {{--    type: 'bar',--}}
        {{--    data: {--}}
        {{--        labels: [@foreach ($quarterly_completed as $quarter) "{{ $quarter['label'] }}", @endforeach],--}}
        {{--        datasets: [{--}}
        {{--            label: "Vehicles",--}}
        {{--            backgroundColor: "#691883",--}}
        {{--            hoverBackgroundColor: "#4a095e",--}}
        {{--            borderColor: "#691883",--}}
        {{--            data: [@foreach ($quarterly_completed as $quarter_order) {{ $quarter_order['data'] }}, @endforeach],--}}
        {{--        }],--}}
        {{--    },--}}
        {{--    options: {--}}
        {{--        scales: {--}}
        {{--            y: {--}}
        {{--                beginAtZero: true,--}}
        {{--            }--}}
        {{--        },--}}
        {{--        plugins: {--}}
        {{--            legend: {--}}
        {{--                display: false--}}
        {{--            }--}}
        {{--        }--}}
        {{--    },--}}
        {{--});--}}
    </script>
@endpush
