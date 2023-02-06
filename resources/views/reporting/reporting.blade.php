@extends('layouts.main', [
    'title' => 'Report & Track',
    'activePage' => 'report-track'
    ])

@section('content')

    <!-- Begin Page Content -->
    <div class="container-xxl">
        <!-- Content Row -->
        <!-- Weekly Placed -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold">Downloads</h6>
            </div>
            <div class="card-body">
                <h5>Registered - Month</h5>
                <div class="row">
                    @foreach($registeredMonths as $month)
                        <div class="col col-1 mb-4">
                            <a href="{{ route('monthly-registered', [\Carbon\Carbon::parse($month)->format('m'), \Carbon\Carbon::parse($month)->format('Y')]) }}" class="btn btn-primary btn-sm h-100 w-full">{{ $month }}</a>
                        </div>
                    @endforeach
                </div>
                <h5 class="mt-4">Registered - Quarters</h5>
                <div class="row">
                    @foreach($registeredQuarters as $quarter)
                        <div class="col col-3 mb-4">
                            <a href="{{ route('quarter-registered', [$quarter['quarter'], $quarter['year']]) }}" class="btn btn-secondary h-100 w-full">Q{{ $quarter['quarter'] }} {{ $quarter['year'] }}</a>
                        </div>
                    @endforeach
                    <div class="col col-3 mb-4">
                        <a href="{{ route('quarter-registered' ) }}" class="btn btn-secondary h-100 w-full">Current</a>
                    </div>
                </div>
                <hr>
                <h5 class="mt-4">Renewal Reports</h5>
                <div class="row">
                    @foreach($financeMonths as $month)
                        <div class="col col-1 mb-4">
                            <a href="{{ route('monthly-finance', [\Carbon\Carbon::parse($month)->format('m'), \Carbon\Carbon::parse($month)->format('Y')]) }}" class="btn btn-primary btn-sm h-100 w-full">{{ $month }}</a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="card shadow mb-4">
            <!-- Card Header - Dropdown -->
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold">Weekly <small>(Orders Placed - Last 6 weeks)</small></h6>
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

        <!-- Monthly Placed -->

        <div class="card shadow mb-4">
            <!-- Card Header - Dropdown -->
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold">Monthly <small>(Orders Placed - Last 6 months)</small>
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

        <!-- Quarterly Placed -->
        <div class="card shadow mb-4">
            <!-- Card Header - Dropdown -->
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold">Quarterly <small>(Orders Placed - Last 4 Quarters)</small></h6>
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
        @endsection

        @push('custom-scripts')
            <script>
                const month_sales = document.getElementById('monthSales');
                const month_sales_chart = new Chart(month_sales, {
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


                const quarterly_sales = document.getElementById('quarterlySales');
                const quarterly_sales_chart = new Chart(quarterly_sales, {
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
            </script>
    @endpush
