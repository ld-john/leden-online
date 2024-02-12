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
                    <label for="registration" class="mb-0 mt-2">Registration</label>
                    <input wire:model.live="registration" type="text" name="registration" id="registration" class="form-control"
                           autocomplete="off"
                    />
                    <label for="orbit_number" class="mb-0 mt-2">Orbit Number</label>
                    <input wire:model.live="orbit_number" type="text" name="orbit_number" id="orbit_number" class="form-control"
                           autocomplete="off"
                    />
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
                    @if($vehicleStatus === 1)
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
                @endif
                <label for="vehicle_status" class="mb-0 mt-2">Status</label>
                <select wire:model.live="vehicleStatus" class="form-control" name="vehicle_status" id="vehicle_status">
                    <option value="">Please Select Status</option>
                    <option value="4">Factory Order</option>
                    @if ($registered_date)
                        <option value="15">In Stock (Registered)</option>
                    @endif
                    <option value="1">In Stock</option>
                    <option value="3">Ready for Delivery</option>
                    <option value="5">Awaiting Delivery Confirmation</option>
                    <option value="6">Delivery Booked</option>
                    @if ($registered_date && $registered_date < $now)
                        <option value="7">Completed Orders</option>
                    @endif
                    <option value="10">Europe VHC</option>
                    <option value="11">UK VHC</option>
                    <option value="12">At Converter</option>
                    <option value="13">Awaiting Ship</option>
                    <option value="14">Recall</option>
                    <option value="16">Damaged/Recalled</option>
                    <option value="17">In Stock (Awaiting Dealer Options)</option>
                    <option value="18">Dealer Transfer</option>
                    <option value="19">Order in Query</option>

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
