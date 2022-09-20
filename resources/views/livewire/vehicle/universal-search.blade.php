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
    </div>
    <table class="table table-bordered">
        <thead>
        <tr class="blue-background text-white">
            <th>ID</th>
            <th>Orbit Number</th>
            <th>Make</th>
            <th>Model</th>
            <th>Ford Order Number</th>
            <th>Status</th>
            <th>Order</th>
            <th>Broker</th>
            <th>Dealer</th>
            <th>Customer</th>
            <th>Actions</th>
        </tr>
        <tr class="bg-light">
            <th class="p-1">
                <input wire:model.debounce:500ms="searchID" type="text" class="form-control" placeholder="Search ID">
            </th>
            <th class="p-1">
                <input wire:model.debounce:500ms="searchOrbitNumber" type="text" class="form-control" placeholder="Search Orbit Number">
            </th>
            <th class="p-1">
                <input wire:model.debounce:500ms="searchMake" type="text" class="form-control" placeholder="Search Make">
            </th>
            <th class="p-1">
                <input wire:model.debounce:500ms="searchModel" type="text" class="form-control" placeholder="Search Model">
            </th>
            <th class="p-1">
                <input wire:model.debounce:500ms="searchFordOrderNumber" type="text" class="form-control" placeholder="Search Ford Order Number">
            </th>
            <th class="p-1">
                <select wire:model="searchStatus" name="status" id="status" class="form-control">
                    <option value="">Select Status</option>
                    @foreach($status as $item)
                        <option value="{{ $item }}">
                            {{ \App\Vehicle::statusMatch($item) }}
                        </option>
                    @endforeach
                </select>
            </th>
            <th class="p-1">
                <input wire:model.debounce:500ms="searchOrder" type="text" class="form-control" placeholder="Search Order">
            </th>
            <th class="p-1">
                <input wire:model.debounce:500ms="searchBroker" type="text" class="form-control" placeholder="Search Broker">
            </th>
            <th class="p-1">
                <input wire:model.debounce:500ms="searchDealer" type="text" class="form-control" placeholder="Search Dealer">
            </th>
            <th class="p-1">
                <input wire:model.debounce:500ms="searchCustomer" type="text" class="form-control" placeholder="Search Customer">
            </th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @forelse($vehicles as $vehicle)
            <tr>
                <td>{{ $vehicle->id }}</td>
                <td>{{ $vehicle->orbit_number }}</td>
                <td>{{ $vehicle->manufacturer?->name }}</td>
                <td>{{ $vehicle->model }}</td>
                <td>{{ $vehicle->ford_order_number }}</td>
                <td>{{ $vehicle->status() }}</td>
                <td>
                    @if($vehicle->order)
                        <a href="{{ route('order.show', $vehicle->order->id) }}" class="btn btn-primary">Leden Order #{{ $vehicle->order->id }}</a>
                    @else
                        Not on Order
                    @endif
                </td>
                <td>
                    {{ $vehicle->broker?->company_name }}
                </td>
                <td>
                    {{ $vehicle->dealer?->company_name }}
                </td>
                <td>
                    {{ $vehicle->order?->customer->name() }}
                </td>
                <td>

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
