<div>
    <div class="d-flex justify-content-between">
        <div class="w-25 p-3 d-flex align-items-center">
            Show
            <select wire:model="paginate" name="" id="" class="form-control mx-2">
                <option value='10'>10</option>
                <option value='25'>25</option>
                <option value='50'>50</option>
                <option value='100'>100</option>
            </select>
            entries
        </div>
    </div>
    <table class="table table-hover table-bordered">
        <thead>
        <tr class="blue-background text-white">
            <th>
                <label for="orbitNumberMissing" class="form-check-label small">Filter Missing Orbit Numbers</label>
                <div class="form-check form-switch">
                    <input wire:model="filterMissingOrbitNumber" type="checkbox" id="orbitNumberMissing" class="form-check-input" role="switch">
                </div>
                ID
            </th>
            <th>Make</th>
            <th>Model</th>
            <th>Ford Order Number</th>
            <th>Derivative</th>
            <th>Engine</th>
            <th>Transmission</th>
            <th>Colour</th>
            <th>Extras</th>
            <th>Type</th>
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
            <th>Dealership</th>
            @if($ringfenced)
                <th>Broker</th>
            @endif
            <th>Status</th>
            <th>Last Updated</th>
            <th>Action</th>
        </tr>
        <tr class="bg-light">
            <th class="p-1">
                <input wire:model="searchID" type="text" class="form-control" placeholder="Search ID">
            </th>
            <th class="p-1">
                <input wire:model.debounce:500ms="searchMake" type="text" class="form-control" placeholder="Search Make">
            </th>
            <th class="p-1">
                <input wire:model.debounce:500ms="searchModel" type="text" class="form-control" placeholder="Search Model">
            </th>
            <th class="p-1">
                <input wire:model.debounce:500ms="searchOrderNumber" type="text" class="form-control" placeholder="Search Ford Order Number">
            </th>
            <th class="p-1">
                <input wire:model.debounce:500ms="searchDerivative" type="text" class="form-control" placeholder="Search Derivative">
            </th>
            <th class="p-1">
                <input wire:model.debounce:500ms="searchEngine" type="text" class="form-control" placeholder="Search Engine">
            </th>
            <th class="p-1">
                <input wire:model.debounce:500ms="searchTransmission" type="text" class="form-control" placeholder="Search Transmission">
            </th>
            <th class="p-1">
                <input wire:model.debounce:500ms="searchColour" type="text" class="form-control" placeholder="Search Colour">
            </th>
            <th></th>
            <th class="p-1">
                <input wire:model.debounce:500ms="searchType" type="text" class="form-control" placeholder="Search Type">
            </th>
            <th class="p-1">
                <input wire:model.debounce:500ms="searchChassis" type="text" class="form-control" placeholder="Search Chassis">
            </th>
            <th class="p-1">
                <input wire:model.debounce:500ms="searchRegistration" type="text" class="form-control" placeholder="Search Registration">
            </th>
            <th class="p-1">
                <input wire:model.debounce:500ms="searchBuildDate" type="date" class="form-control" placeholder="Search Planned Build Date">
            </th>
            <th class="p-1">
                <input wire:model.debounce:500ms="searchDueDate" class="form-control" placeholder="Search Due Date" type="date">

            </th>
            <th class="p-1">
                <input wire:model.debounce:500ms="searchDealer" type="text" class="form-control" placeholder="Search Dealer">
            </th>
            @if($ringfenced)
            <th class="p-1">
                <input wire:model.debounce:500ms="searchBroker" type="text" class="form-control" placeholder="Search Broker">
            </th>
            @endif
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
            <th></th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @forelse($vehicles as $vehicle)
            <tr>
                <td>
                    {{ $vehicle->id }}
                    @if($vehicle->orbit_number)
                        <br>(Orbit Number: {{ $vehicle->orbit_number }})
                    @endif
                </td>
                <td>{{$vehicle->manufacturer->name}}</td>
                <td>{{ $vehicle->model }}</td>
                <td>{{ $vehicle->ford_order_number }}</td>
                <td>{{ $vehicle->derivative }}</td>
                <td>{{ $vehicle->engine }}</td>
                <td>{{ $vehicle->transmission }}</td>
                <td>{{ $vehicle->colour }}</td>
                <td>
                    <livewire:vehicle.fit-options-modal :vehicle="$vehicle->id" :key="time().$vehicle->id" />
                </td>
                <td>{{ $vehicle->type }}</td>
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
                <td>
                    @if ($vehicle->dealer)
                        {{ $vehicle->dealer->company_name }}
                    @endif
                </td>
                @if($ringfenced)
                    <td>
                    @if($vehicle->broker)
                        {{ $vehicle->broker->company_name }}
                    @endif
                    </td>
                @endif
                <td>{{ $vehicle->status() }}</td>
                <td>
                    {{ \Carbon\Carbon::parse($vehicle->updated_at)->format( 'd/m/Y h:ia') }}
                </td>
                <td width="120px">
                    <a href="{{route('vehicle.show', $vehicle->id)}}" class="btn btn-primary" data-toggle="tooltip" title="View Vehicle Information"><i class="far fa-eye"></i></a>
                    @can('admin')
                        <a href="{{route('vehicle.edit', $vehicle->id)}}" class="btn btn-warning" data-toggle="tooltip" title="Edit Vehicle Information"><i class="fas fa-edit"></i></a>
                        <a href="{{route('order.reserve', $vehicle->id)}}" class="btn btn-primary" data-toggle="tooltip" title="Create order with Vehicle"><i class="fas fa-plus-square"></i></a>
                        <a data-toggle="tooltip" title="Delete Vehicle">
                            <livewire:vehicle.delete-vehicle :vehicle="$vehicle->id" :key="time().$vehicle->id" />
                        </a>
                        @if($vehicle->ring_fenced_stock === 1)
                            <a wire:click="unRingFenceVehicle({{ $vehicle->id }})" class="btn btn-primary" data-toggle="tooltip" title="Move to Leden Stock"><i class="fa-solid fa-car"></i></a>
                        @else
                            <livewire:vehicle.ring-fence-modal :vehicle="$vehicle->id" :key="time().$vehicle->id" />
                        @endif
                    @endcan
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="17"><strong>No Results found</strong></td>
            </tr>
        @endforelse
        </tbody>
    </table>
    <div class="d-flex justify-content-between">
        @if(!$vehicles->isEmpty())
        <p>Showing {{ $vehicles->firstItem() }} - {{ $vehicles->lastItem() }} of {{$vehicles->total()}}</p>
        @endif
        <div>
            {{ $vehicles->links() }}
        </div>
    </div>
</div>
