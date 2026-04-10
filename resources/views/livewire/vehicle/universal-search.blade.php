<div class="p-2">
    <div class="d-flex justify-content-between">
        <div class="w-25 p-3 d-flex align-items-center">
            Show
            <select wire:model.live="paginate" class="form-control mx-2" name="" id="">
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>
            Vehicles
        </div>
        <div class="d-flex align-items-center gap-2">
            <a wire:click="downloadCurrentData" class="btn btn-success text-white">Download Current View</a>
            <div class="dropdown d-inline-block">
                <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    Status Filters
                </button>
                <ul class="dropdown-menu p-3">
                    <li>
                        <div class="form-check form-switch">
                            <label for="deliveriesBookedFilter" class="form-check-label">Include Deliveries Booked</label>
                            <input wire:model.live="deliveriesBookedFilter" type="checkbox" id="deliveriesBookedFilter"
                                   class="form-check-input" role="switch">
                        </div>
                    </li>
                    <li>
                        <div class="form-check form-switch">
                            <label for="completedOrdersFilter" class="form-check-label">Include Completed Orders</label>
                            <input wire:model.live="completedOrdersFilter" type="checkbox" id="completedOrdersFilter"
                                   class="form-check-input" role="switch">
                        </div>
                    </li>
                </ul>
            </div>

            <div class="dropdown d-inline-block">
                <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    Date Filters
                </button>
                <ul class="dropdown-menu p-3">
                    <li>
                        <div class="form-check form-switch">
                            <input wire:model.live="filterBuildDate" type="checkbox" id="filterBuildDate" class="form-check-input">
                            <label for="filterBuildDate" class="form-check-label">Filter Build Date</label>
                        </div>
                    </li>
                    <li>
                        <div class="form-check form-switch">
                            <input wire:model.live="filterDueDate" type="checkbox" id="filterDueDate" class="form-check-input">
                            <label for="filterDueDate" class="form-check-label">Filter Due Date</label>
                        </div>
                    </li>
                </ul>
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
            <th>Make / Model</th>
            <th>Details</th>
            <th>Chassis</th>
            <th>Registration</th>
            <th>Planned Build Date</th>
            <th>Due Date</th>
            <th>Status</th>
            <th>Broker</th>
            <th>Broker Reference</th>
            <th>Fit Options</th>
            <th>Website Location</th>
        </tr>
        <tr class="bg-light">
            <th class="p-1">
                <input wire:model.live.debounce:500ms="searchOrder" type="text" class="form-control"
                       placeholder="Search Order">
            </th>
            <th class="p-1">
                <input wire:model.live.debounce:500ms="searchCustomer" type="text" class="form-control"
                       placeholder="Search Customer">
            </th>
            <th class="p-1">
                <input wire:model.live.debounce:500ms="searchFordOrderNumber" type="text" class="form-control"
                       placeholder="Search Ford Order Number">
            </th>
            <th class="p-1">
                <input wire:model.live.debounce:500ms="searchOrbitNumber" type="text" class="form-control"
                       placeholder="Search Orbit Number">
            </th>
            <th class="p-1">
                <select wire:model.live="searchType" name="type" id="type" class="form-select">
                    <option value="">Select Type</option>
                    @foreach($type as $item)
                        <option value="{{ $item }}">
                            {{ $item }}
                        </option>
                    @endforeach
                </select>
            </th>
            <th class="p-1">
                <input wire:model.live.debounce:500ms="searchMake" type="text" class="form-control"
                       placeholder="Search Make">
            </th>
            <th class="p-1">
                <input wire:model.live.debounce:500ms="searchDetails" type="text" class="form-control"
                       placeholder="Search Details">
            </th>
            <th class="p-1">
                <input wire:model.live.debounce:500ms="searchChassis" type="text" class="form-control"
                       placeholder="Search Chassis">
            </th>
            <th class="p-1">
                <input wire:model.live.debounce:500ms="searchRegistration" type="text" class="form-control"
                       placeholder="Search Registration">
            </th>
            <th class="p-1">
                <input wire:model.live.debounce:500ms="searchBuildDate" type="date" class="form-control"
                       placeholder="Search Build Date">
            </th>
            <th class="p-1">
                <input wire:model.live.debounce:500ms="searchDueDate" type="date" class="form-control"
                       placeholder="Search Due Date">
            </th>
            <th class="p-1">
                <select wire:model.live="searchStatus" name="status" id="status" class="form-select">
                    <option value="">Select Status</option>
                    @foreach($status as $item)
                        <option value="{{ $item->value }}">
                            {{ $item->label() }}
                        </option>
                    @endforeach
                </select>
            </th>
            <th class="p-1">
                <input wire:model.live.debounce:500ms="searchBroker" type="text" class="form-control"
                       placeholder="Search Broker">
            </th>
            <th class="p-1">
                <input wire:model.live.debounce:500ms="searchBrokerRef" type="text" class="form-control"
                       placeholder="Search Broker Reference">
            </th>
            <th></th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @forelse($vehicles as $vehicle)
            <tr>
                <td>
                    @if($vehicle->order)
                        <a href="{{ route('order.show', $vehicle->order->id) }}" class="btn btn-primary">Leden Order
                            #{{ $vehicle->order->id }}</a>
                        @if(count($vehicle->order->tacticalOffers) > 0)
                            <span class="badge rounded-pill bg-primary" title="Tactical Offers">TO: {{ count($vehicle->order->tacticalOffers)  }}</span>
                        @endif
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
                <td>{{ $vehicle->manufacturer?->name }} {{ $vehicle->model }}</td>
                <td class="p-0">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><strong>Derivative:</strong><br> {{ $vehicle->derivative }}</li>
                        <li class="list-group-item"><strong>Engine:</strong><br> {{ $vehicle->engine }}</li>
                        <li class="list-group-item"><strong>Transmission:</strong><br> {{ $vehicle->transmission }}</li>
                        <li class="list-group-item"><strong>Colour:</strong><br> {{ $vehicle->colour }}</li>
                    </ul>
                </td>
                <td>
                    <span class="fw-bold">{{ $vehicle->chassis_prefix }}</span>
                    <small class="text-muted">
                        {{ $vehicle->chassis }}
                    </small>
                </td>
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
                <td>{{ $vehicle->vehicle_status->label() }}</td>
                <td class="p-0">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">{{ $vehicle?->order->broker->company_name ?? ''}}</li>
                        @if( $vehicle?->order?->finance_broker )
                            <li class="list-group-item">{{ $vehicle->order->finance_broker->company_name ?? '' }}</li>
                        @endif
                    </ul>
                </td>
                <td>{{ $vehicle->order?->broker_ref }}</td>
                <td class="text-center">
                    @if(count($vehicle->factoryFitOptions()) > 0)
                        <span class="badge rounded-pill bg-info" title="Factory Fit">F: {{ count($vehicle->factoryFitOptions()) }}</span>
                    @endif
                    @if(count($vehicle->dealerFitOptions()) > 0)
                        <span class="badge rounded-pill bg-warning text-dark" title="Dealer Fit">D: {{ count($vehicle->dealerFitOptions()) }}</span>
                    @endif
                </td>
                <td>
                    @php
                        $button = $vehicle->websiteLocation();
                    @endphp
                    <a class="btn btn-{{ $button['status'] }}"
                       @if($button['route']) href="{{ route($button['route']) }}" @endif>{{ $button['location'] }}</a>
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
