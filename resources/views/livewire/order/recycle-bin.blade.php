<div class="table-responsive">
    <table class="table table-bordered table-striped">
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
            <th>Action</th>
        </tr>
        <tr class="bg-light">
            <th class="p-1">
                <input wire:model.blur="searchID" type="text" class="form-control"
                       placeholder="Search ID">
            </th>
            <th class="p-1">
                <input wire:model.blur="searchModel" type="text" class="form-control"
                       placeholder="Search Model">
            </th>
            <th class="p-1">
                <input wire:model.blur="searchDerivative" type="text" class="form-control"
                       placeholder="Search Derivatives">
            </th>
            <th class="p-1">
                <input wire:model.blur="searchOrderNumber" type="text" class="form-control"
                       placeholder="Search Ford Order Number">
            </th>
            <th class="p-1">
                <input wire:model.blur="searchOrbitNumber" type="text" class="form-control"
                       placeholder="Search Orbit Number">
            </th>
            <th class="p-1">
                <input wire:model.blur="searchReg" type="text" class="form-control"
                       placeholder="Search Registration">
            </th>
            <th class="p-1">
                <input wire:model.blur="searchBuildDate" type="date" class="form-control"
                       placeholder="Search Build Date">
            </th>
            <th class="p-1">
                <input wire:model.blur="searchDueDate" type="date" class="form-control"
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
                <input wire:model.blur="searchCustomer" type="text" class="form-control"
                       placeholder="Search Customer">
            </th>
            <th class="p-1">
                <input wire:model.blur="searchBrokerRef" type="text" class="form-control"
                       placeholder="Search Broker Order Ref">
            </th>
            <th class="p-1">
                <input wire:model.blur="searchBroker" type="text" class="form-control"
                       placeholder="Search Broker">
            </th>
            <th class="p-1">
                <input wire:model.blur="searchDealer" type="text" class="form-control"
                       placeholder="Search Dealer">
            </th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($orders as $order)
            <tr>
                <td>{{ $order->id }}</td>
                <td>{{ $order->vehicle?->model }}</td>
                <td>{{ $order->vehicle?->derivative }}</td>
                <td>{{ $order->vehicle?->ford_order_number }}</td>
                <td>{{ $order->vehicle?->orbit_number }}</td>
                <td>{{ $order->vehicle?->reg }}</td>
                @if ( empty( $order->vehicle?->build_date) || $order->vehicle?->build_date == '0000-00-00 00:00:00')
                    <td></td>
                @else
                    <td>{{ \Carbon\Carbon::parse($order->vehicle?->build_date ?? '')->format( 'd/m/Y' )}}</td>
                @endif
                @if ( empty( $order->vehicle?->due_date) || $order->vehicle?->due_date == '0000-00-00 00:00:00')
                    <td></td>
                @else
                    <td>{{ \Carbon\Carbon::parse($order->vehicle?->due_date ?? '')->format( 'd/m/Y' )}}</td>
                @endif
                <td>{{ $order->vehicle?->vehicle_status->label() }}</td>
                <td>
                    {{ $order->customer->customer_name ?? ''}}
                </td>
                <td>
                    {{ $order->broker_ref }}
                </td>
                <td class="p-0">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item bg-transparent">{{ $order->broker->company_name ?? ''}}</li>
                        @if( $order->finance_broker )
                            <li class="list-group-item bg-transparent">{{ $order->finance_broker->company_name ?? '' }}</li>
                        @endif
                    </ul>
                </td>
                <td>{{ $order->dealer->company_name ?? ''}}</td>
                <td>
                    <div class="d-grid grid-cols-2 gap-2">
                        <button
                                type="button"
                                wire:click="restore({{ $order->id }})"
                                wire:loading.attr="disabled"
                                wire:target="restore({{ $order->id }})"
                                class="btn btn-sm btn-outline-success"
                        >
                            {{-- Default Label --}}
                            <span wire:loading.remove wire:target="restore({{ $order->id }})">
                                <i class="fa-solid fa-trash-arrow-up"></i>
                            </span>

                            {{-- Loading Label --}}
                            <span wire:loading wire:target="restore({{ $order->id }})">
                            <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                            </span>
                        </button>
                        <button
                                type="button"
                                wire:click="forceDelete({{ $order->id }})"
                                wire:confirm="Are you sure you want to permanently delete this order?"
                                wire:loading.attr="disabled"
                                wire:target="forceDelete({{ $order->id }})"
                                class="btn btn-sm btn-outline-danger"
                                title="PERMANENTLY DELETE"
                        >
                            {{-- Default Label --}}
                            <span wire:loading.remove wire:target="forceDelete({{ $order->id }})">
                                <i class="fa-solid fa-trash-can"></i>
                            </span>

                            {{-- Loading Label --}}
                            <span wire:loading wire:target="forceDelete({{ $order->id }})">
                            <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                            </span>
                        </button>
                    </div>
                </td>
            </tr>

        @endforeach

        </tbody>
    </table>
    <div class="d-flex justify-content-center mt-3">
        {{ $orders->links() }}
    </div>
</div>
