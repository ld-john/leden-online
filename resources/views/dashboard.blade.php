@extends('layouts.main', [
    'title' => 'Dashboard',
    'activePage' => 'dashboard'
    ])

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid force-min-height">

        <!-- Content Row -->
        <div class="row">

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-l-blue shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-l-blue text-uppercase mb-1">Vehicles in Stock</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $in_stock }}</div>
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
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-l-blue text-uppercase mb-1">Orders Placed</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $orders_placed }}</div>
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
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-l-blue text-uppercase mb-1">Ready for Delivery</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $ready_for_delivery }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-truck-loading fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-l-blue shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-l-blue text-uppercase mb-1">Completed Orders</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $completed_orders }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clipboard-check fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
            <div class="col-lg-6">
                <div class="card shadow mb-4">
                    <!-- Card Header - Dropdown -->
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-l-blue">Notifications</h6>
                        <div class="dropdown no-arrow">
                            <a class="dropdown-toggle" role="button" id="notificationsDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="notificationsDropdown" x-placement="bottom-end" style="position: absolute; will-change: transform; top: 0; left: 0; transform: translate3d(17px, 19px, 0px);">
                                <div class="dropdown-header">Actions</div>
                                <a class="dropdown-item" href="{{ route('notifications') }}">View all notifications</a>
                            </div>
                        </div>
                    </div>
                    <!-- Card Body -->
                    <div class="card-body">
                        @if (count($notifications) > 0)
                            @foreach ($notifications as $notification)
                                <a href="{{ route('order.show', $notification->data['order_id']) }}" class="notification">
                                    <div class="row">
                                        <div class="col-md-1">
                                            <div class="icon-circle blue-background">
                                                @if ($notification->data['type'] == 'vehicle')
                                                    <i class="fas fa-car text-white"></i>
                                                @else
                                                    <i class="fas fa-flag text-white"></i>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-11">
                                            <div class="small text-gray-500">{{ date('l jS F Y \a\t g:ia', strtotime($notification->created_at)) }}</div>
                                            <span class="@if ($notification->read_at == null) font-weight-bold @endif ">{{ $notification->data['message'] }}</span>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        @else
                            You have no notifications to display
                        @endif
                    </div>
                </div>
            </div>

            <!-- Messages -->
            <div class="col-lg-6">
                <div class="card shadow mb-4">
                    <!-- Card Header - Dropdown -->
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-l-blue">Messages</h6>
                        <div class="dropdown no-arrow">
                            <a class="dropdown-toggle" href="#" role="button" id="messagesDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="messagesDropdown" x-placement="bottom-end" style="position: absolute; will-change: transform; top: 0; left: 0; transform: translate3d(17px, 19px, 0px);">
                                <div class="dropdown-header">Actions</div>
                                <a class="dropdown-item" href="{{ route('messages') }}">View all messages</a>
                            </div>
                        </div>
                    </div>
                    <!-- Card Body -->
                    <div class="card-body">
                        @if (UserMessages::getAllUnreadMessages(Auth::user()->id)->count() > 0)
                            @foreach (UserMessages::getUnreadMessages(6, Auth::user()->id) as $show_message)
                                <a href="{{ route('message.view', $show_message->message_group_id) }}" class="notification">
                                    <div class="row">
                                        <div class="col-md-1">
                                            <div class="icon-circle blue-background">
                                                <span class="text-white font-weight-bold">{{ substr(ucfirst($show_message->firstname), 0, 1) }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-11">
                                            <div class="small text-gray-500">{{ date('l jS F Y \a\t g:ia', strtotime($show_message->created_at)) }}</div>
                                            <span class="@if ($show_message->read_at == null) font-weight-bold @endif ">{{ $show_message->subject }}</span>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        @else
                            You have no new messages to display
                        @endif
                    </div>
                </div>
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
