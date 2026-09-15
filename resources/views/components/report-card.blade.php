@props([
    'title',
    'subtitle',
    'canvasId',
    'routeName',
    'periodKey',
    'labelKey',
    'placedData',
    'registeredData',
    'completedData'
])

<div class="card shadow mb-4">
    <!-- Card Header -->
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold">{{ $title }} <small>({{ $subtitle }})</small></h6>
    </div>

    <!-- Card Body -->
    <div class="card-body">
        <!-- Chart Canvas -->
        <div class="chart-area" style="position: relative; height: 40vh;">
            <canvas id="{{ $canvasId }}"></canvas>
        </div>

        {{-- Group our data into a single array so we can loop through it easily --}}
        @php
            $reportCategories = [
                'Orders Placed' => ['type' => 'placed', 'data' => $placedData],
                'Vehicles Registered' => ['type' => 'registered', 'data' => $registeredData],
                'Orders Completed' => ['type' => 'completed', 'data' => $completedData],
            ];
        @endphp

        {{-- Loop through each category to generate the headers and buttons --}}
        @foreach($reportCategories as $header => $category)
            <h5>{{ $header }}</h5>

            @foreach($category['data'] as $item)
                {{-- Only show the button if there is data --}}
                @if($item['data'] > 0)
                    <a class="btn btn-primary btn-sm"
                       href="{{ route($routeName, [$category['type'], $item['year'], $item[$periodKey]]) }}">

                        {{-- If it's a monthly report, we need to append the year to the label --}}
                        @if($periodKey === 'month')
                            {{ $item[$labelKey] }} {{ $item['year'] }}
                        @else
                            {{ $item[$labelKey] }}
                        @endif
                    </a>
                @endif
            @endforeach
        @endforeach
    </div>
</div>
