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
                    <p class="mb-0 mt-2">Order number</p>
                    <input wire:model="order_number" type="text" name="order_number" id="order_number" class="form-control"
                           autocomplete="off" placeholder="e.g. K0047"
                    />
                    <p class="mb-0 mt-2">Registration</p>
                    <input wire:model="registration" type="text" name="registration" id="registration" class="form-control"
                           autocomplete="off" placeholder="e.g. WM63 NKZ"
                    />
                    <p class="mb-0 mt-2">Orbit Number</p>
                    <input wire:model="orbit_number" type="text" name="orbit_number" id="orbit_number" class="form-control"
                           autocomplete="off" placeholder="e.g. 66653275"
                    />
                    <p class="mb-0 mt-2">Due Date</p>
                    <input wire:model="due_date" type="text" name="due_date" id="due_date" class="form-control"
                           autocomplete="off" placeholder="e.g. 30/03/2019"
                    />
                    <p class="mb-0 mt-2">Planned Build Date</p>
                    <input wire:model="build_date" type="text" name="build_date" id="build_date" class="form-control"
                            autocomplete="off" placeholder="e.g. 30/03/2019">
                    <p class="mb-0 mt-2">Order Date</p>
                    <input wire:model="order_date" type="text" name="order_date" id="order_date" class="form-control"
                           autocomplete="off" placeholder="e.g. 30/03/2019">
                    <p class="mb-0 mt-2">Status</p>
                    <select wire:model="vehicleStatus" class="form-control" name="vehicle_status" id="vehicle_status">
                        <option value="">Please Select Status</option>
                        <option value="4">
                            Factory Order
                        </option>
                        <option value="1">
                            In Stock
                        </option>
                        <option value="3">
                            Ready for Delivery
                        </option>
                        <option value="6">
                            Delivery Booked
                        </option>
                        <option value="7">
                            Completed Orders
                        </option>
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
