<div class="d-inline-block">
    <button
            type="button"
            class="btn btn-danger delete-order"
            wire:click="toggleDeleteModal"
    >
        <i class="fas fa-trash"></i>
    </button>
    <div class="modal @if($modalShow) show @endif">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Delete Order - {{ $order->id }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" wire:click="toggleDeleteModal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Do you also want to remove the associated vehicle {{ $vehicle->manufacturer->name ?? '' }} {{ $vehicle->model ?? '' }} - Orbit Number {{ $vehicle->orbit_number ?? 'N/a' }}? <br>
                    <input wire:model.lazy="deleteVehicle" name="deleteVehicle" type="radio" value="yes" /> Yes <br>
                    <input wire:model.lazy="deleteVehicle" name="deleteVehicle" type="radio" value="no" /> No <br>

                    @if($deleteVehicle === 'no')
                        Does the Vehicle Status need to change?
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
                        </select>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" wire:click="toggleDeleteModal">Close</button>
                    <button type="button" class="btn btn-danger" wire:click="deleteOrder">Delete</button>
                </div>
            </div>
        </div>
    </div>
</div>
