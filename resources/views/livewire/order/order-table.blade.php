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
    @if( $view === 'order')
        <table class="table table-bordered">
            <thead>
            <tr class="blue-background text-white">
                <th>Leden Order #</th>
                <th>Model</th>
                <th>Derivative</th>
                <th>Ford Order Number</th>
                <th>Orbit Number</th>
                <th>Registration</th>
                <th>Planned Build Date</th>
                <th>Due Date</th>
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
                    <input wire:model.debounce:500ms="searchID" type="text" class="form-control" placeholder="Search ID">
                </th>
                <th class="p-1">
                    <input wire:model.debounce:500ms="searchModel" type="text" class="form-control" placeholder="Search Model">
                </th>
                <th class="p-1">
                    <input wire:model.debounce:500ms="searchDerivative" type="text" class="form-control" placeholder="Search Derivatives">
                </th>
                <th class="p-1">
                    <input wire:model.debounce:500ms="searchOrderNumber" type="text" class="form-control" placeholder="Search Ford Order Number">
                </th>
                <th class="p-1">
                    <input wire:model.debounce:500ms="searchOrbitNumber" type="text" class="form-control" placeholder="Search Orbit Number">
                </th>
                <th class="p-1">
                    <input wire:model.debounce:500ms="searchReg" type="text" class="form-control" placeholder="Search Registration">
                </th>
                <th class="p-1">
                    <input wire:model.debounce:500ms="searchBuildDate" type="text" class="form-control" placeholder="Search Build Date">
                </th>
                <th class="p-1">
                    <input wire:model.debounce:500ms="searchDueDate" type="text" class="form-control" placeholder="Search Due Date">
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
                    <input wire:model.debounce:500ms="searchCustomer" type="text" class="form-control" placeholder="Search Customer">
                </th>
                <th class="p-1">
                    <input wire:model.debounce:500ms="searchBrokerRef" type="text" class="form-control" placeholder="Search Broker Order Ref">
                </th>
                <th class="p-1">
                    <input wire:model.debounce:500ms="searchBroker" type="text" class="form-control" placeholder="Search Broker">
                </th>
                <th class="p-1">
                    <input wire:model.debounce:500ms="searchDealer" type="text" class="form-control" placeholder="Search Dealer">
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
                    <td>{{ $order->broker->company_name ?? ''}}</td>
                    <td>{{ $order->dealer->company_name ?? ''}}</td>
                    <td>{{ \Carbon\Carbon::parse($order->updated_at)->format('d/m/Y h:ia') }}</td>
                    <td width="120px">
                        <div class="d-flex flex-wrap">
                            <a href="{{route('order.show', $order->id)}}" class="btn btn-primary" data-toggle="tooltip" title="View Order"><i class="far fa-eye"></i></a>
                            @can('admin')
                                <a href="{{route('order.edit', $order->id)}}" class="btn btn-warning" data-toggle="tooltip" title="Edit Order"><i class="fas fa-edit"></i></a>
                                <a data-toggle="tooltip" title="Copy Order"><livewire:order.duplicate-order :order="$order->id" :key="time().$order->id" /></a>
                                <a data-toggle="tooltip" title="Delete Order"><livewire:order.delete-order :order="$order->id" :vehicle="$order->vehicle" :key="time().$order->id" /></a>
                                <a data-toggle="tooltip" title="Quick Edit"> <livewire:order.quick-edit-order :order="$order->id" :vehicle="$order->vehicle" view="order" :key="time().$order->id" /></a>
                                @if($order->delivery_date && $order->delivery_date !== '0000-00-00 00:00:00' && $order->vehicle->vehicle_status === 1)
                                    <a data-toggle="tooltip" title="Request Delivery" href="{{ route('delivery.create', $order->id) }}" class="btn btn-primary"><i class="fa-solid fa-truck"></i></a>
                                @endif
                            @endcan
                            @can('broker')
                                @if($order->delivery_date && $order->delivery_date !== '0000-00-00 00:00:00' && $order->vehicle->vehicle_status === 1)
                                    <a data-toggle="tooltip" title="Request Delivery" href="{{ route('delivery.create', $order->id) }}" class="btn btn-primary"><i class="fa-solid fa-truck"></i></a>
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
    @elseif($view === 'delivery')
        <table class="table table-bordered">
            <thead>
            <tr class="blue-background text-white">
                <th>ID</th>
                <th>Model</th>
                <th>Derivative</th>
                <th>Ford Order Number</th>
                <th>Orbit Number</th>
                <th>Registration</th>
                <th>Planned Build Date</th>
                <th>Delivery Date</th>
                <th>Due Date</th>
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
                    <input wire:model.debounce:500ms="searchID" type="text" class="form-control" placeholder="Search ID">
                </th>
                <th class="p-1">
                    <input wire:model.debounce:500ms="searchModel" type="text" class="form-control" placeholder="Search Model">
                </th>
                <th class="p-1">
                    <input wire:model.debounce:500ms="searchDerivative" type="text" class="form-control" placeholder="Search Derivatives">
                </th>
                <th class="p-1">
                    <input wire:model.debounce:500ms="searchOrderNumber" type="text" class="form-control" placeholder="Search Ford Order Number">
                </th>
                <th class="p-1">
                    <input wire:model.debounce:500ms="searchOrbitNumber" type="text" class="form-control" placeholder="Search Orbit Number">
                </th>
                <th class="p-1">
                    <input wire:model.debounce:500ms="searchReg" type="text" class="form-control" placeholder="Search Registration">
                </th>
                <th class="p-1">
                    <input wire:model.debounce:500ms="searchBuildDate" type="text" class="form-control" placeholder="Search Build Date">
                </th>
                <th class="p-1">
                    <input wire:model.debounce:500ms="searchDeliveryDate" type="text" class="form-control" placeholder="Search Delivery Date">
                </th>
                <th class="p-1">
                    <input wire:model.debounce:500ms="searchDueDate" type="text" class="form-control" placeholder="Search Due Date">
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
                    <input wire:model.debounce:500ms="searchCustomer" type="text" class="form-control" placeholder="Search Customer">
                </th>
                <th class="p-1">
                    <input wire:model.debounce:500ms="searchBrokerRef" type="text" class="form-control" placeholder="Search Broker Order Ref">
                </th>
                <th class="p-1">
                    <input wire:model.debounce:500ms="searchBroker" type="text" class="form-control" placeholder="Search Broker">
                </th>
                <th class="p-1">
                    <input wire:model.debounce:500ms="searchDealer" type="text" class="form-control" placeholder="Search Dealer">
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
                    <td>{{ $order->broker->company_name ?? ''}}</td>
                    <td>{{ $order->dealer->company_name ?? ''}}</td>
                    <td>{{ \Carbon\Carbon::parse($order->updated_at)->format('d/m/Y h:ia') }}</td>
                    <td width="120px">
                        <div class="d-flex flex-wrap">
                            <a href="{{route('order.show', $order->id)}}" class="btn btn-primary" data-toggle="tooltip" title="View Order"><i class="far fa-eye"></i></a>
                            @can('admin')
                                <a data-toggle="tooltip" title="Delete Order"><livewire:order.delete-order :order="$order->id" :vehicle="$order->vehicle" :key="time().$order->id" /></a>
                                <a data-toggle="tooltip" title="Quick Edit"> <livewire:order.quick-edit-order :order="$order->id" :vehicle="$order->vehicle" view="delivery" :key="time().$order->id" /></a>
                                @if($order->vehicle->vehicle_registered_on && $order->vehicle->vehicle_registered_on !== '0000-00-00 00:00:00' && $order->vehicle->vehicle_registered_on < $now)
                                    <a wire:click="markCompleted({{$order->id}})" data-toggle="tooltip" title="Mark as Complete" class="btn btn-success"><i class="fa-solid fa-check"></i></a>
                                @endif
                                @if($order->delivery)
                                    <a href="{{ route('delivery.show', $order->delivery_id) }}" class="btn btn-primary" data-toggle="tooltip" title="View Delivery"><i class="fa-solid fa-truck"></i></a>
                                @endif
                            @endcan
                            @can('broker')
                                @if($order->delivery)
                                    <a href="{{ route('delivery.show', $order->delivery_id) }}" class="btn btn-primary" data-toggle="tooltip" title="View Delivery"><i class="fa-solid fa-truck"></i></a>
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
