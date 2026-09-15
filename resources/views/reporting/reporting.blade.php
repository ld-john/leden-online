@php use Carbon\Carbon; @endphp

@extends('layouts.main', [
    'title' => 'Report & Track',
    'activePage' => 'report-track'
    ])

@section('content')
    <div class="container-xxl">
        <!-- Downloads Section -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold">Downloads</h6>
            </div>
            <div class="card-body">
                <h5>Bonds</h5>
                @if($outstanding_bonds)
                    <a
                        href="{{ route('bonds.outstanding-download') }}"
                        class="btn btn-primary h-100 w-full">
                            Download Outstanding Bonds report
                    </a>
                @else
                    <p>All bonds have been processed</p>
                @endif
                @if($grouped_bonds)
                <h5>Completed Bonds</h5>
                <div class="row">
                    @foreach($grouped_bonds as $year => $bonds)
                        <div class="col col-3 mb-4">
                            <a
                                href="{{ route('bonds.completed-download', [$year]) }}"
                                class="btn btn-primary h-100 w-full">
                                    {{ $year }} ({{ count($bonds) }})
                            </a>
                        </div>
                    @endforeach
                </div>
                @endif
            </div>
            <div class="card-body">
                <h5>Registered—Month</h5>
                <div class="row">
                    <div class="accordion" id="reportingMonths">
                        @foreach($registeredMonths as $year => $months)
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heading{{$loop->iteration}}">
                                    <button class="accordion-button @if(!$loop->last) collapsed @endif" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapse{{$loop->iteration}}"
                                            aria-expanded="true" aria-controls="collapse{{$loop->iteration}}">
                                        {{ $year }}
                                    </button>
                                </h2>
                                <div id="collapse{{$loop->iteration}}"
                                     class="accordion-collapse collapse @if($loop->last) show @endif"
                                     aria-labelledby="heading{{$loop->iteration}}" data-bs-parent="#reportingMonths">
                                    <div class="accordion-body">
                                        <div class="row">
                                            @foreach($months as $month)
                                                @php
                                                    $date = Carbon::createFromFormat('Y m d', $month);
                                                @endphp
                                                <div class="col col-3 mb-4">
                                                    <a href="{{ route('monthly-registered', [$date->format('m'), $date->format('Y')]) }}"
                                                       class="btn btn-primary h-100 w-full">
                                                        {{ $date->format('F') }}
                                                    </a>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                </div>
                <h5 class="mt-4">Registered - Quarters</h5>
                <div class="row">
                    @foreach($registeredQuarters as $quarter)
                        <div class="col col-3 mb-4">
                            <a href="{{ route('quarter-registered', [$quarter['quarter'], $quarter['year']]) }}"
                               class="btn btn-secondary h-100 w-full">Q{{ $quarter['quarter'] }} {{ $quarter['year'] }}</a>
                        </div>
                    @endforeach
                </div>
                <hr>
                <h5 class="mt-4">Renewal Reports</h5>
                <div class="row">
                    <div class="accordion" id="financeMonths">
                        @foreach($financeMonths as $year => $months)
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="flush-heading{{$loop->iteration}}">
                                    <button class="accordion-button @if(!$loop->first) collapsed @endif" type="button"
                                            data-bs-toggle="collapse"
                                            data-bs-target="#flush-collapse{{$loop->iteration}}" aria-expanded="false"
                                            aria-controls="flush-collapse{{$loop->iteration}}">
                                        {{ $year }}
                                    </button>
                                </h2>
                                <div id="flush-collapse{{$loop->iteration}}"
                                     class="accordion-collapse collapse @if($loop->first) show @endif"
                                     aria-labelledby="flush-heading{{$loop->iteration}}"
                                     data-bs-parent="#financeMonths">
                                    <div class="accordion-body">
                                        <div class="row">
                                            @foreach($months as $month)
                                                @php
                                                    $date = Carbon::parse($month['label']);
                                                @endphp
                                                <div class="col col-3 mb-4">
                                                    <a href="{{ route('monthly-finance', [$date->format('m'), $date->format('Y')]) }}"
                                                       class="btn btn-primary h-100 w-full">
                                                        {{ $month['label'] }}
                                                    </a>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
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
                        <a class="btn btn-primary btn-sm" href="{{ route('weekly-download', ['placed', $weekly['year'], $weekly['week']]) }}">
                            {{ $weekly['label'] }}
                        </a>
                @endforeach
                <h5>Vehicles Registered</h5>
                @foreach($weekly_registered as $weekly)
                    @if($weekly['data'] === 0)
                        @continue
                    @endif
                        <a class="btn btn-primary btn-sm" href="{{ route('weekly-download', ['registered', $weekly['year'], $weekly['week']]) }}">
                            {{ $weekly['label'] }}
                        </a>
                @endforeach
                <h5>Orders Completed</h5>
                @foreach($weekly_completed as $weekly)
                    @if($weekly['data'] === 0)
                        @continue
                    @endif
                        <a class="btn btn-primary btn-sm" href="{{ route('weekly-download', ['completed', $weekly['year'], $weekly['week']]) }}">
                            {{ $weekly['label'] }}
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
                        <a href="{{ route('monthly-download', ['placed', $monthly_sale['year'], $monthly_sale['month']]) }}" class="btn btn-primary btn-sm">
                            {{ $monthly_sale['month_label'] }} {{ $monthly_sale['year'] }}
                        </a>
                @endforeach
                <h5>Vehicles Registered</h5>
                @foreach($monthly_registered as $monthly)
                    @if($monthly['data'] === 0)
                        @continue
                    @endif
                        <a href="{{ route('monthly-download', ['completed', $monthly['year'], $monthly['month']]) }}" class="btn btn-primary btn-sm">
                            {{ $monthly['month_label'] }} {{ $monthly['year'] }}
                        </a>
                @endforeach
                <h5>Orders Completed</h5>
                @foreach($monthly_completed as $monthly)
                    @if($monthly['data'] === 0)
                        @continue
                    @endif
                    <a href="/reporting/monthly-completed/{{$monthly['year']}}/{{$monthly['month']}}"
                       class="btn btn-primary btn-sm">{{$monthly['month_label']}} {{$monthly['year']}}</a>
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
                        <a class="btn btn-primary btn-sm" href="{{ route('quarterly-download', ['placed', $quarterly['year'], $quarterly['quarter']]) }}">
                            {{ $quarterly['label'] }}
                        </a>
                @endforeach
                <h5>Vehicles Registered</h5>
                @foreach($quarterly_registered as $quarterly)
                    @if($quarterly['data'] === 0)
                        @continue
                    @endif
                        <a class="btn btn-primary btn-sm" href="{{ route('quarterly-download', ['registered', $quarterly['year'], $quarterly['quarter']]) }}">
                            {{ $quarterly['label'] }}
                        </a>
                @endforeach
                <h5>Orders Completed</h5>
                @foreach($quarterly_completed as $quarterly)
                    @if($quarterly['data'] === 0)
                        @continue
                    @endif
                        <a class="btn btn-primary btn-sm" href="{{ route('quarterly-download', ['completed', $quarterly['year'], $quarterly['quarter']]) }}">
                            {{ $quarterly['label'] }}
                        </a>
                @endforeach
            </div>
        </div>
    </div>
@endsection

        @push('custom-scripts')
            <script>
                const month_sales = document.getElementById('monthSales');
                const month_sales_chart = new Chart(month_sales, {
                    type: 'bar',
                    data: {
                        labels: @json(collect($monthly_sales)->pluck('month_label')),
                        datasets: [
                            {
                                label: "Orders Placed",
                                backgroundColor: "#E0C44D",
                                hoverBackgroundColor: "#947F23",
                                borderColor: "#E0C44D",
                                data: @json(collect($monthly_sales)->pluck('data')),
                            },
                            {
                                label: "Vehicles Registered",
                                backgroundColor: "#00b43d",
                                hoverBackgroundColor: "#007a29",
                                borderColor: "#00b43d",
                                data: @json(collect($monthly_registered)->pluck('data')),
                            },
                            {
                                label: "Orders Completed",
                                backgroundColor: "#0b61be",
                                hoverBackgroundColor: "#02356b",
                                borderColor: "#0b61be",
                                data: @json(collect($monthly_completed)->pluck('data')),
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
                        labels: @json(collect($weekly_sales)->pluck('label')),
                        datasets: [{
                            label: "Orders Placed",
                            backgroundColor: "#E0C44D",
                            hoverBackgroundColor: "#947F23",
                            borderColor: "#E0C44D",
                            data: @json(collect($weekly_sales)->pluck('data')),
                        },
                            {
                                label: "Vehicles Registered",
                                backgroundColor: "#00b43d",
                                hoverBackgroundColor: "#007a29",
                                borderColor: "#00b43d",
                                data: @json(collect($weekly_registered)->pluck('data')),
                            },
                            {
                                label: "Orders Completed",
                                backgroundColor: "#0b61be",
                                hoverBackgroundColor: "#02356b",
                                borderColor: "#0b61be",
                                data: @json(collect($weekly_completed)->pluck('data')),
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
                        labels: @json(collect($quarterly_sales)->pluck('label')),
                        datasets: [{
                            label: "Orders Placed",
                            backgroundColor: "#E0C44D",
                            hoverBackgroundColor: "#947F23",
                            borderColor: "#E0C44D",
                            data: @json(collect($quarterly_sales)->pluck('data')),
                        },
                            {
                                label: "Registered Vehicles",
                                backgroundColor: "#00b43d",
                                hoverBackgroundColor: "#007a29",
                                borderColor: "#00b43d",
                                data: @json(collect($quarterly_registered)->pluck('data'))
                            },
                            {
                                label: "Completed Orders",
                                backgroundColor: "#0b61be",
                                hoverBackgroundColor: "#02356b",
                                borderColor: "#0b61be",
                                data: @json(collect($quarterly_completed)->pluck('data'))
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
