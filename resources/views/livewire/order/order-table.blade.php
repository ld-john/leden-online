<div>
    <div class="d-flex justify-content-between">
        <div class="w-25 p-3 d-flex align-items-center">
            Show
            <select wire:model.live="paginate" name="" id="" class="form-control mx-2">
                <option value='10'>10</option>
                <option value='25'>25</option>
                <option value='50'>50</option>
                <option value='100'>100</option>
            </select>
            entries
        </div>
    </div>
    @if( $view === 'order')
        <table class="table table-bordered">
            <thead>
            <tr class="blue-background text-white">
                <th>Leden Order #</th>
                <th>Model</th>
                <th>Derivative</th>
                @can('broker')
                    <th>Colour</th>
                @endcan
                <th>
                    Ford Order Number

                </th>
                <th>
                    <label for="orbitNumberMissing" class="form-check-label small">Filter Missing Orbit Numbers</label>
                    <div class="form-check form-switch">
                        <input wire:model.live="filterMissingOrbitNumber" type="checkbox" id="orbitNumberMissing"
                               class="form-check-input" role="switch">
                    </div>
                    Orbit Number
                </th>
                <th>Registration</th>
                <th>
                    <label for="BuildDateFilter" class="form-check-label small">Filter Build Date</label>
                    <div class="form-check form-switch">
                        <input wire:model.live="filterBuildDate" type="checkbox" id="BuildDateFilter"
                               class="form-check-input" role="switch">
                    </div>
                    Planned Build Date
                </th>
                <th>
                    <label for="DueDateFilter" class="form-check-label small">Filter Due Date</label>
                    <div class="form-check form-switch">
                        <input wire:model.live="filterDueDate" type="checkbox" id="DueDateFilter" class="form-check-input"
                               role="switch">
                    </div>
                    Due Date
                </th>
                <th>Status</th>
                <th>Customer</th>
                <th>Broker Order Ref</th>
                <th>Broker</th>
                <th>Dealership</th>
                <th>Last Updated</th>
                <th>Action</th>
            </tr>
            <tr class="bg-light">
                <th class="p-1">
                    <input wire:model.live.debounce:500ms="searchID" type="text" class="form-control"
                           placeholder="Search ID">
                </th>
                <th class="p-1">
                    <input wire:model.live.debounce:500ms="searchModel" type="text" class="form-control"
                           placeholder="Search Model">
                </th>
                <th class="p-1">
                    <input wire:model.live.debounce:500ms="searchDerivative" type="text" class="form-control"
                           placeholder="Search Derivatives">
                </th>
                @can('broker')
                    <th></th>
                @endcan
                <th class="p-1">
                    <input wire:model.live.debounce:500ms="searchOrderNumber" type="text" class="form-control"
                           placeholder="Search Ford Order Number">
                </th>
                <th class="p-1">
                    <input wire:model.live.debounce:500ms="searchOrbitNumber" type="text" class="form-control"
                           placeholder="Search Orbit Number">
                </th>
                <th class="p-1">
                    <input wire:model.live.debounce:500ms="searchReg" type="text" class="form-control"
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
                            <option value="{{ $item }}">
                                {{ \App\Models\Vehicle::statusMatch($item) }}
                            </option>
                        @endforeach
                    </select>
                </th>
                <th class="p-1">
                    <input wire:model.live.debounce:500ms="searchCustomer" type="text" class="form-control"
                           placeholder="Search Customer">
                </th>
                <th class="p-1">
                    <input wire:model.live.debounce:500ms="searchBrokerRef" type="text" class="form-control"
                           placeholder="Search Broker Order Ref">
                </th>
                <th class="p-1 flex flex-col gap-1">
                    <input wire:model.live.debounce:500ms="searchBroker" type="text" class="form-control"
                           placeholder="Search Broker">
                    <input wire:model.live.debounce:500ms="searchFinanceBroker" type="text" class="form-control"
                           placeholder="Search Finance Broker">
                </th>
                <th class="p-1">
                    <input wire:model.live.debounce:500ms="searchDealer" type="text" class="form-control"
                           placeholder="Search Dealer">
                </th>
                <th></th>
                <th class="p-1"></th>
            </tr>
            </thead>
            <tbody>
            @forelse($orders as $order)
                <tr>
                    <td>{{ $order->id ?? '' }}</td>
                    <td>{{ $order->vehicle->model ?? ''}}</td>
                    <td>{{ $order->vehicle->derivative ?? ''}}</td>
                    @can('broker')
                        <td>{{ $order->vehicle->colour ?? '--'}}</td>
                    @endcan
                    <td>{{ $order->vehicle->ford_order_number ?? ''}}</td>
                    <td>{{ $order->vehicle->orbit_number }}</td>
                    <td>{{ $order->vehicle->reg ?? ''}}</td>
                    @if ( empty( $order->vehicle->build_date) || $order->vehicle->build_date == '0000-00-00 00:00:00')
                        <td></td>
                    @else
                        <td>{{ \Carbon\Carbon::parse($order->vehicle->build_date ?? '')->format( 'd/m/Y' )}}</td>
                    @endif
                    @if ( empty( $order->vehicle->due_date) || $order->vehicle->due_date == '0000-00-00 00:00:00')
                        <td></td>
                    @else
                        <td>{{ \Carbon\Carbon::parse($order->vehicle->due_date )->format( 'd/m/Y' )}}</td>
                    @endif

                    <td>{{ $order->vehicle->status() }}</td>

                    <td>
                        {{ $order->customer->customer_name ?? ''}}
                    </td>
                    <td>{{ $order->broker_ref ?? ''}}</td>
                    <td class="p-0">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">{{ $order->broker->company_name ?? ''}}</li>
                            @if( $order->finance_broker )
                            <li class="list-group-item">{{ $order->finance_broker->company_name ?? '' }}</li>
                            @endif
                        </ul>
                    </td>
                    <td>{{ $order->dealer->company_name ?? ''}}</td>
                    <td>{{ \Carbon\Carbon::parse($order->updated_at)->format('d/m/Y h:ia') }}</td>
                    <td width="120px">
                        <div class="d-grid grid-cols-2 gap-2">
                            <a
                                    href="{{route('order.show', $order->id)}}"
                                    class="btn btn-primary"
                                    data-toggle="tooltip"
                                    title="View Order"
                            >
                                <i class="far fa-eye"></i>
                            </a>
                            @can('admin')
                                <a
                                        href="{{route('order.edit', $order->id)}}"
                                        class="btn btn-secondary"
                                        data-toggle="tooltip"
                                        title="Edit Order"
                                >
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a
                                        class="btn btn-info"
                                        data-toggle="tooltip"
                                        title="Copy Order"
                                >
                                    <livewire:order.duplicate-order :order="$order->id" :key="time().$order->id"/>
                                </a>
                                <a
                                        class="btn btn-danger"
                                        data-toggle="tooltip"
                                        title="Delete Order"
                                >
                                    <livewire:order.delete-order :order="$order->id" :vehicle="$order->vehicle" :key="time().$order->id"/>
                                </a>
                                <a
                                        class="btn btn-warning"
                                        data-toggle="tooltip"
                                        title="Quick Edit"
                                >
                                    <livewire:order.quick-edit-order :order="$order->id" :vehicle="$order->vehicle" view="order" :key="time().$order->id"/>
                                </a>
                                @if($order->delivery_date && $order->delivery_date !== '0000-00-00 00:00:00' && $order->vehicle->vehicle_status === 1)
                                    <a
                                            data-toggle="tooltip"
                                            title="Request Delivery"
                                            href="{{ route('delivery.create', $order->id) }}"
                                            class="btn btn-dark"
                                    >
                                        <i class="fa-solid fa-truck"></i>
                                    </a>
                                @endif
                            @endcan
                            @can('broker')
                                @if($order->delivery_date && $order->delivery_date !== '0000-00-00 00:00:00' && $order->vehicle->vehicle_status === 1)
                                    <a
                                            data-toggle="tooltip"
                                            title="Request Delivery"
                                            href="{{ route('delivery.create', $order->id) }}"
                                            class="btn btn-dark"
                                    >
                                        <i class="fa-solid fa-truck"></i>
                                    </a>
                                @endif
                            @endcan
                        </div>
                        @can('admin')
                            <livewire:order.contract-confirmation-checkbox :order="$order->id" :key="time().$order->id"></livewire:order.contract-confirmation-checkbox>
                        @endcan
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="15"> No Results found - Please try again</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    @elseif($view === 'delivery')
        <table class="table table-bordered">
            <thead>
            <tr class="blue-background text-white">
                <th>ID</th>
                <th>Model</th>
                <th>Derivative</th>
                <th>Ford Order Number</th>
                <th>
                    <label for="orbitNumberMissing" class="form-check-label small">Filter Missing Orbit Numbers</label>
                    <div class="form-check form-switch">
                        <input wire:model.live="filterMissingOrbitNumber" type="checkbox" id="orbitNumberMissing"
                               class="form-check-input" role="switch">
                    </div>
                    Orbit Number
                </th>
                <th>Registration</th>
                <th>
                    <label for="BuildDateFilter" class="form-check-label small">Filter Build Date</label>
                    <div class="form-check form-switch">
                        <input wire:model.live="filterBuildDate" type="checkbox" id="BuildDateFilter"
                               class="form-check-input" role="switch">
                    </div>
                    Planned Build Date
                </th>
                <th>
                    <label for="DeliveryDateFilter" class="form-check-label small">Filter Delivery Date</label>
                    <div class="form-check form-switch">
                        <input wire:model.live="filterDeliveryDate" type="checkbox" id="DeliveryDateFilter"
                               class="form-check-input" role="switch">
                    </div>
                    Delivery Date
                </th>
                <th>
                    <label for="DueDateFilter" class="form-check-label small">Filter Due Date</label>
                    <div class="form-check form-switch">
                        <input wire:model.live="filterDueDate" type="checkbox" id="DueDateFilter" class="form-check-input"
                               role="switch">
                    </div>
                    Due Date
                </th>
                <th>Status</th>
                <th>Customer</th>
                <th>Broker Order Ref</th>
                <th>Broker</th>
                <th>Dealership</th>
                <th>Last Updated</th>
                <th>Action</th>
            </tr>
            <tr class="bg-light">
                <th class="p-1">
                    <input wire:model.live.debounce:500ms="searchID" type="text" class="form-control"
                           placeholder="Search ID">
                </th>
                <th class="p-1">
                    <input wire:model.live.debounce:500ms="searchModel" type="text" class="form-control"
                           placeholder="Search Model">
                </th>
                <th class="p-1">
                    <input wire:model.live.debounce:500ms="searchDerivative" type="text" class="form-control"
                           placeholder="Search Derivatives">
                </th>
                <th class="p-1">
                    <input wire:model.live.debounce:500ms="searchOrderNumber" type="text" class="form-control"
                           placeholder="Search Ford Order Number">
                </th>
                <th class="p-1">
                    <input wire:model.live.debounce:500ms="searchOrbitNumber" type="text" class="form-control"
                           placeholder="Search Orbit Number">
                </th>
                <th class="p-1">
                    <input wire:model.live.debounce:500ms="searchReg" type="text" class="form-control"
                           placeholder="Search Registration">
                </th>
                <th class="p-1">
                    <input wire:model.live.debounce:500ms="searchBuildDate" type="date" class="form-control"
                           placeholder="Search Build Date">
                </th>
                <th class="p-1">
                    <input wire:model.live.debounce:500ms="searchDeliveryDate" type="date" class="form-control"
                           placeholder="Search Delivery Date">
                </th>
                <th class="p-1">
                    <input wire:model.live.debounce:500ms="searchDueDate" type="date" class="form-control"
                           placeholder="Search Due Date">
                </th>
                <th class="p-1">
                    <select wire:model.live="searchStatus" name="status" id="status" class="form-select">
                        <option value="">Select Status</option>
                        @foreach($status as $item)
                            <option value="{{ $item }}">
                                {{ \App\Models\Vehicle::statusMatch($item) }}
                            </option>
                        @endforeach
                    </select>
                </th>
                <th class="p-1">
                    <input wire:model.live.debounce:500ms="searchCustomer" type="text" class="form-control"
                           placeholder="Search Customer">
                </th>
                <th class="p-1">
                    <input wire:model.live.debounce:500ms="searchBrokerRef" type="text" class="form-control"
                           placeholder="Search Broker Order Ref">
                </th>
                <th class="p-1">
                    <input wire:model.live.debounce:500ms="searchBroker" type="text" class="form-control"
                           placeholder="Search Broker">
                </th>
                <th class="p-1">
                    <input wire:model.live.debounce:500ms="searchDealer" type="text" class="form-control"
                           placeholder="Search Dealer">
                </th>
                <th></th>
                <th class="p-1"></th>
            </tr>
            </thead>
            <tbody>
            @forelse($orders as $order)
                <tr>
                    <td>{{ $order->id ?? '' }}</td>
                    <td>{{ $order->vehicle->model ?? ''}}</td>
                    <td>{{ $order->vehicle->derivative ?? ''}}</td>
                    <td>{{ $order->vehicle->ford_order_number ?? ''}}</td>
                    <td>{{ $order->vehicle->orbit_number }}</td>
                    <td>{{ $order->vehicle->reg ?? ''}}</td>
                    @if ( empty( $order->vehicle->build_date) || $order->vehicle->build_date == '0000-00-00 00:00:00')
                        <td></td>
                    @else
                        <td>{{ \Carbon\Carbon::parse($order->vehicle->build_date ?? '')->format( 'd/m/Y' )}}</td>
                    @endif
                    @if ( empty( $order->delivery_date) || $order->delivery_date == '0000-00-00 00:00:00')
                        <td></td>
                    @else
                        <td>{{ \Carbon\Carbon::parse($order->delivery_date ?? '')->format( 'd/m/Y' )}}</td>
                    @endif
                    @if ( empty( $order->due_date) || $order->due_date == '0000-00-00 00:00:00')
                        <td></td>
                    @else
                        <td>{{ \Carbon\Carbon::parse($order->due_date ?? '')->format( 'd/m/Y' )}}</td>
                    @endif
                    <td>{{ $order->vehicle->status() }}</td>
                    <td>
                        {{ $order->customer->customer_name ?? ''}}
                    </td>
                    <td>
                        {{ $order->broker_ref }}
                    </td>
                    <td class="p-0">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">{{ $order->broker->company_name ?? ''}}</li>
                            @if( $order->finance_broker )
                                <li class="list-group-item">{{ $order->finance_broker->company_name ?? '' }}</li>
                            @endif
                        </ul>
                    </td>
                    <td>{{ $order->dealer->company_name ?? ''}}</td>
                    <td>{{ \Carbon\Carbon::parse($order->updated_at)->format('d/m/Y h:ia') }}</td>
                    <td width="120px">
                        <div class="d-grid grid-cols-2 gap-2">
                            <a href="{{route('order.show', $order->id)}}" class="btn btn-primary" data-toggle="tooltip"
                               title="View Order"><i class="far fa-eye"></i></a>
                            @can('admin')
                                <a
                                        data-toggle="tooltip"
                                        title="Delete Order"
                                        class="btn btn-danger"
                                >
                                    <livewire:order.delete-order :order="$order->id" :vehicle="$order->vehicle" :key="time().$order->id"/>
                                </a>
                                <a
                                        data-toggle="tooltip"
                                        title="Quick Edit"
                                        class="btn btn-warning"
                                >
                                    <livewire:order.quick-edit-order :order="$order->id" :vehicle="$order->vehicle" view="delivery" :key="time().$order->id"/>
                                </a>
                                @if($order->vehicle->vehicle_registered_on && $order->vehicle->vehicle_registered_on !== '0000-00-00 00:00:00' && $order->vehicle->vehicle_registered_on < $now)
                                    <a
                                            wire:click="markCompleted({{$order->id}})"
                                            data-toggle="tooltip"
                                            title="Mark as Complete"
                                            class="btn btn-success"
                                    >
                                        <i class="fa-solid fa-check"></i>
                                    </a>
                                @endif
                                @if($order->delivery)
                                    <a
                                            href="{{ route('delivery.show', $order->delivery_id) }}"
                                            class="btn btn-dark"
                                            data-toggle="tooltip"
                                            title="View Delivery"
                                    >
                                        <i class="fa-solid fa-truck"></i>
                                    </a>
                                @endif
                            @endcan
                            @can('broker')
                                @if($order->delivery)
                                    <a href="{{ route('delivery.show', $order->delivery_id) }}" class="btn btn-primary"
                                       data-toggle="tooltip" title="View Delivery"><i class="fa-solid fa-truck"></i></a>
                                @endif
                            @endcan
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="15"> No Results found - Please try again</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    @endif
    <div class="d-flex justify-content-between">
        @if(!$orders->isEmpty())
            <p>Showing {{ $orders->firstItem() }} - {{ $orders->lastItem() }} of {{$orders->total()}}</p>
        @endif
        <div>
            {{ $orders->links() }}
        </div>
    </div>

</div>
