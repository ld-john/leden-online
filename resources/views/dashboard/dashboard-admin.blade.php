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
                            <canvas id="runReport" style="height: 350px"></canvas>
                        </div>
                        <div class="row g-1 row-cols-4">
                            @foreach($vehicle_statuses as $key => $status)

                                @continue($key === 'Awaiting Delivery Confirmation' || $key === 'Completed Orders' || $key === 'Recall')

                                @if($status !== 0)
                                    <div class="col col-3">
                                        <a class="btn btn-primary w-100 h-100 btn-sm" href="{{ route('export.' . Str::replace(')', '', Str::replace('(', '',Str::snake($key)))) }}">Download {{ $key }}</a>
                                    </div>
                                @endif
                            @endforeach
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

        let labels = [
            @foreach($pie_labels as $label)
            '{{$label['label']}}',
            @endforeach
        ]

        let data = [
            @foreach($pie_labels as $label)
                '{{$label['value']}}',
            @endforeach
        ]

        let runReport = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: labels,
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
                    data: data
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
