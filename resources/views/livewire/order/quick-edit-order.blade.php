<div class="d-inline-block">
    <button
            type="button"
            class="btn btn-warning"
            wire:click="toggleEditModal"
    >
        <i class="fa-solid fa-pencil"></i>
    </button>
    <div class="modal @if($modalShow) show @endif">

        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Quick Edit Order - {{ $order->id }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" wire:click="toggleEditModal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @if($view === 'order')
                        <p class="mb-0 mt-2">Order number</p>
                        <input wire:model="order_number" type="text" name="order_number" id="order_number" class="form-control"
                               autocomplete="off"
                        />
                        <p class="mb-0 mt-2">Registration</p>
                        <input wire:model="registration" type="text" name="registration" id="registration" class="form-control"
                               autocomplete="off"
                        />
                        <p class="mb-0 mt-2">Orbit Number</p>
                        <input wire:model="orbit_number" type="text" name="orbit_number" id="orbit_number" class="form-control"
                               autocomplete="off"
                        />
                        <p class="mb-0 mt-2">Due Date</p>
                        <input wire:model="due_date" type="date" name="due_date" id="due_date" class="form-control"
                               autocomplete="off"
                        />
                        <p class="mb-0 mt-2">Planned Build Date</p>
                        <input wire:model="build_date" type="date" name="build_date" id="build_date" class="form-control"
                               autocomplete="off" />
                        <p class="mb-0 mt-2">Order Date</p>
                        <input wire:model="order_date" type="date" name="order_date" id="order_date" class="form-control"
                               autocomplete="off" />
                    @endif
                    @if($view === 'delivery')
                        <p class="mb-0 mt-2">Registration Date</p>
                        <input wire:model="registered_date" type="date" name="registration_date" id="registration_date" class="form-control"
                               autocomplete="off" />
                    @endif
                    <p class="mb-0 mt-2">Status</p>
                    <select wire:model="vehicleStatus" class="form-control" name="vehicle_status" id="vehicle_status">
                        <option value="">Please Select Status</option>
                        <option value="4">
                            Factory Order
                        </option>
                        <option value="1">
                            In Stock
                        </option>
                        @if ($registered_date)
                            <option value="15">In Stock (Registered)</option>
                        @endif
                        <option value="3">
                            Ready for Delivery
                        </option>
                        <option value="6">
                            Delivery Booked
                        </option>
                        @if ($registered_date && $registered_date <= $now)
                            <option value="7">
                                Completed Orders
                            </option>
                        @endif
                        <option value="10">
                            Europe VHC
                        </option>
                        <option value="11">
                            UK VHC
                        </option>
                        <option value="12">
                            At Converter
                        </option>
                        <option value="13">
                            Awaiting Ship
                        </option>
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
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" wire:click="toggleEditModal">Close</button>
                    <button type="button" class="btn btn-primary" wire:click="saveOrder">Save</button>
                </div>
            </div>
        </div>
    </div>
</div>
