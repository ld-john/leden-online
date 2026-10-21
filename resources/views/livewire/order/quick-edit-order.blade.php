<div class="d-inline-block">
    <i
            class="fa-solid fa-pencil"
            data-bs-toggle="offcanvas"
            data-bs-target="#offcanvas-{{$order->id}}"
            aria-controls="offcanvasExample"
    ></i>

    <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvas-{{$order->id}}" aria-labelledby="offcanvasExampleLabel" wire:ignore.self>
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="offcanvasExampleLabel">Quick Edit Order - {{ $order->id }}</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <div>
                @if($view === 'order')
                    <label for="order_number" class="mb-0 mt-2">Order number</label>
                    <input wire:model.live="order_number" type="text" name="order_number" id="order_number" class="form-control"
                           autocomplete="off"
                    />
                    <label for="broker_order_ref">Broker Order Ref</label>
                    <input wire:model.live="broker_order_ref" type="text" name="broker_order_ref" id="broker_order_ref" class="form-control" autocomplete="off" />
                    <label for="registration" class="mb-0 mt-2">Registration</label>
                    <input wire:model.live="registration" type="text" name="registration" id="registration" class="form-control"
                           autocomplete="off"
                    />
                    <label for="orbit_number" class="mb-0 mt-2">Orbit Number</label>
                    <input wire:model.live="orbit_number" type="text" name="orbit_number" id="orbit_number" class="form-control"
                           autocomplete="off"
                    />
                    <label for="delivery_month">Desired Delivery Month</label>
                    <select wire:model.live="delivery_month" id="delivery_month" class="form-select">
                        <option value="">---</option>
                        <option value="Jan">January</option>
                        <option value="Feb">February</option>
                        <option value="Mar">March</option>
                        <option value="Apr">April</option>
                        <option value="May">May</option>
                        <option value="Jun">June</option>
                        <option value="Jul">July</option>
                        <option value="Aug">August</option>
                        <option value="Sep">September</option>
                        <option value="Oct">October</option>
                        <option value="Nov">November</option>
                        <option value="Dec">December</option>
                    </select>
                    <label for="compound">Compound</label>
                    <select name="compound" id="compound" wire:model.live="compound" class="form-control">
                        <option value="">---</option>
                        @foreach($compounds as $vehicle_compound)
                            <option value="{{ $vehicle_compound->name }}">{{ $vehicle_compound->name }}</option>
                        @endforeach
                    </select>
                    <label for="order_date" class="mb-0 mt-2">Order Date</label>
                    <input wire:model.live="order_date" type="date" name="order_date" id="order_date" class="form-control"
                           autocomplete="off" />
                    <label for="build_date" class="mb-0 mt-2">Planned Build Date</label>
                    <input wire:model.live="build_date" type="date" name="build_date" id="build_date" class="form-control"
                           autocomplete="off" />
                    <label for="due_date" class="mb-0 mt-2">Due Date</label>
                    <input wire:model.live="due_date" type="date" name="due_date" id="due_date" class="form-control"
                           autocomplete="off"
                    />
                    @if($vehicleStatus === \App\Enums\VehicleStatus::IN_STOCK)
                        <label for="delivery_date" class="mb-0 mt-2">Proposed Delivery Date</label>
                        <input wire:model.live="delivery_date" id="delivery_date" type="date" name="delivery_date" class="form-control" autocomplete="off">
                    @endif
                @endif
                @if($view === 'delivery')

                    <label for="delivery_date" class="mb-0 mt-2">Proposed Delivery Date</label>
                    <input wire:model.live="delivery_date" id="delivery_date" type="date" name="delivery_date" class="form-control" autocomplete="off">
                    <label for="registration_date" class="mb-0 mt-2">Registration Date</label>
                    <input wire:model.live="registered_date" type="date" name="registration_date" id="registration_date" class="form-control"
                           autocomplete="off" />
                    @if($order->delivery)
                    <label for="delivery_agent" class="mb-0 mt-2">Delivery Agent</label>
                        <select name="agent" id="delivery_agent" class="form-select" wire:model.live="selectedDeliveryAgent">
                            <option value="">-- Select an Agent --</option>
                            @foreach($this->deliveryAgents as $agent)
                                <option value="{{ $agent->id }}">{{ $agent->company_name }}</option>
                            @endforeach
                        </select>
                    @endif
                @endif
                <label for="vehicle_status" class="mb-0 mt-2">Status</label>
                <select wire:model.live="vehicleStatus" class="form-select" name="vehicle_status" id="vehicle_status">
                    <option value="">Please Select Status</option>
                    @foreach($this->availableStatuses as $status)
                        <option value="{{ $status->value }}">
                            {{ $status->label() }}
                        </option>

                    @endforeach
                </select>
            </div>
            @if($errors->count())
                <div class="alert alert-danger alert-dismissible fade show m-5">
                    <h4 class="alert-heading"><i class="fa fa-exclamation-triangle"></i> There are some issues.</h4>
                    <hr>
                    <ul>
                        {!! implode($errors->all('<li>:message</li>')) !!}
                    </ul>
                </div>
            @endif
            <div class="my-4">
                <button type="button" class="btn btn-primary" wire:click="saveOrder">Save</button>
            </div>
        </div>
    </div>
</div>
