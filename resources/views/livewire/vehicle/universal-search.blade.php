<div class="p-2">
    <div class="d-flex justify-content-between">
        <div class="w-25 p-3 d-flex align-items-center">
            Show
            <select wire:model="paginate" class="form-control mx-2" name="" id="">
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>
            Vehicles
        </div>
        <div>
            <a wire:click="downloadCurrentData" class="btn btn-success text-white">Download Current View</a>
            <div class="form-check form-switch">
                <label for="deliveriesBookedFilter" class="form-check-label">Include Deliveries Booked</label>
                <input wire:model="deliveriesBookedFilter" type="checkbox" id="deliveriesBookedFilter" class="form-check-input" role="switch">
            </div>
            <div class="form-check form-switch">
                <label for="completedOrdersFilter" class="form-check-label">Include Completed Orders</label>
                <input wire:model="completedOrdersFilter" type="checkbox" id="completedOrdersFilter" class="form-check-input" role="switch">
            </div>
        </div>
    </div>
    <table class="table table-bordered">
        <thead>
        <tr class="blue-background text-white">
            <th>Order ID</th>
            <th>Customer Name</th>
            <th>Ford Order Number</th>
            <th>Orbit Number</th>
            <th>Vehicle Type</th>
            <th>Make</th>
            <th>Model</th>
            <th>Details</th>
            <th>Chassis Prefix</th>
            <th>Chassis</th>
            <th>Registration</th>
            <th>
                <label for="BuildDateFilter" class="form-check-label small">Filter Build Date</label>
                <div class="form-check form-switch">
                    <input wire:model="filterBuildDate" type="checkbox" id="BuildDateFilter" class="form-check-input" role="switch">
                </div>
                Planned Build Date
            </th>
            <th>
                <label for="DueDateFilter" class="form-check-label small">Filter Due Date</label>
                <div class="form-check form-switch">
                    <input wire:model="filterDueDate" type="checkbox" id="DueDateFilter" class="form-check-input" role="switch">
                </div>
                Due Date
            </th>
            <th>Status</th>
            <th>Broker</th>
            <th>Broker Reference</th>
            <th>Website Location</th>
        </tr>
        <tr class="bg-light">
            <th class="p-1">
                <input wire:model.debounce:500ms="searchOrder" type="text" class="form-control" placeholder="Search Order">
            </th>
            <th class="p-1">
                <input wire:model.debounce:500ms="searchCustomer" type="text" class="form-control" placeholder="Search Customer">
            </th>
            <th class="p-1">
                <input wire:model.debounce:500ms="searchFordOrderNumber" type="text" class="form-control" placeholder="Search Ford Order Number">
            </th>
            <th class="p-1">
                <input wire:model.debounce:500ms="searchOrbitNumber" type="text" class="form-control" placeholder="Search Orbit Number">
            </th>
            <th class="p-1">
                <select wire:model="searchType" name="type" id="type" class="form-select">
                    <option value="">Select Type</option>
                    @foreach($type as $item)
                        <option value="{{ $item }}">
                            {{ $item }}
                        </option>
                    @endforeach
                </select>
            </th>
            <th class="p-1">
                <input wire:model.debounce:500ms="searchMake" type="text" class="form-control" placeholder="Search Make">
            </th>
            <th class="p-1">
                <input wire:model.debounce:500ms="searchModel" type="text" class="form-control" placeholder="Search Model">
            </th>
            <th class="p-1">
                <input wire:model.debounce:500ms="searchDetails" type="text" class="form-control" placeholder="Search Details">
            </th>
            <th class="p-1">
                <input wire:model.debounce:500ms="searchChassisPrefix" type="text" class="form-control" placeholder="Search Chassis Prefix">
            </th>
            <th class="p-1">
                <input wire:model.debounce:500ms="searchChassis" type="text" class="form-control" placeholder="Search Chassis">
            </th>
            <th class="p-1">
                <input wire:model.debounce:500ms="searchRegistration" type="text" class="form-control" placeholder="Search Registration">
            </th>
            <th class="p-1">
                <input wire:model.debounce:500ms="searchBuildDate" type="date" class="form-control" placeholder="Search Build Date">
            </th>
            <th class="p-1">
                <input wire:model.debounce:500ms="searchDueDate" type="date" class="form-control" placeholder="Search Due Date">
            </th>
            <th class="p-1">
                <select wire:model="searchStatus" name="status" id="status" class="form-select">
                    <option value="">Select Status</option>
                    @foreach($status as $item)
                        <option value="{{ $item }}">
                            {{ \App\Vehicle::statusMatch($item) }}
                        </option>
                    @endforeach
                </select>
            </th>
            <th class="p-1">
                <input wire:model.debounce:500ms="searchBroker" type="text" class="form-control" placeholder="Search Broker">
            </th>
            <th class="p-1">
                <input wire:model.debounce:500ms="searchBrokerRef" type="text" class="form-control" placeholder="Search Broker Reference">
            </th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @forelse($vehicles as $vehicle)
            <tr>
                <td>
                    @if($vehicle->order)
                        <a href="{{ route('order.show', $vehicle->order->id) }}" class="btn btn-primary">Leden Order #{{ $vehicle->order->id }}</a>
                    @else
                        Not on Order
                    @endif
                </td>
                <td>
                    {{ $vehicle->order?->customer->name() }}
                </td>
                <td>{{ $vehicle->ford_order_number }}</td>
                <td>{{ $vehicle->orbit_number }}</td>
                <td>{{ $vehicle->type }}</td>
                <td>{{ $vehicle->manufacturer?->name }}</td>
                <td>{{ $vehicle->model }}</td>
                <td class="p-0">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><strong>Derivative:</strong><br> {{ $vehicle->derivative }}</li>
                        <li class="list-group-item"><strong>Engine:</strong><br> {{ $vehicle->engine }}</li>
                        <li class="list-group-item"><strong>Transmission:</strong><br> {{ $vehicle->transmission }}</li>
                        <li class="list-group-item"><strong>Colour:</strong><br> {{ $vehicle->colour }}</li>
                    </ul>
                </td>
                <td>{{ $vehicle->chassis_prefix }}</td>
                <td>{{ $vehicle->chassis }}</td>
                <td>{{ $vehicle->reg }}</td>
                @if ( empty( $vehicle->build_date) || $vehicle->build_date == '0000-00-00 00:00:00')
                    <td></td>
                @else
                    <td>{{ \Carbon\Carbon::parse($vehicle->build_date ?? '')->format( 'd/m/Y' )}}</td>
                @endif
                @if(empty($vehicle->due_date || $vehicle->due_date == '0000-00-00 00:00:00'))
                    <td></td>
                @else
                    <td>{{ \Carbon\Carbon::parse($vehicle->due_date)->format('d/m/Y') }}</td>
                @endif
                <td>{{ $vehicle->status() }}</td>
                <td>
                    {{ $vehicle->broker?->company_name }}
                </td>
                <td>{{ $vehicle->order?->broker_ref }}</td>
                <td>
                    @php
                        $button = $vehicle->websiteLocation();
                    @endphp
                    <a class="btn btn-{{ $button['status'] }}" @if($button['route']) href="{{ route($button['route']) }}" @endif>{{ $button['location'] }}</a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="117"><strong>No Results found</strong></td>
            </tr>
        @endforelse
        </tbody>
    </table>
    <div class="d-flex justify-content-between">
        @if($vehicles->isNotEmpty())
            <p>Showing {{ $vehicles->firstItem() }} - {{ $vehicles->lastItem() }} of {{ $vehicles->total() }}</p>
        @endif
        <div>{{ $vehicles->links() }}</div>
    </div>
</div>
