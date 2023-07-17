<div class="d-inline-block">
    <i class="fas fa-trash text-white" wire:click="toggleDeleteModal"></i>
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
                    <label>
                        <input wire:model.lazy="deleteVehicle" name="deleteVehicle" type="radio" value="yes" />
                        Yes
                    </label><br>
                    <label>
                        <input wire:model.lazy="deleteVehicle" name="deleteVehicle" type="radio" value="no" />
                        No
                    </label><br>

                    @if($deleteVehicle === 'no')
                        <label for="vehicle_status">Does the Vehicle Status need to change?</label>
                        <select wire:model="vehicleStatus" class="form-select" name="vehicle_status" id="vehicle_status">
                            <option value="">Please Select Status</option>
                            @foreach($vehicle_status as $key => $status)
                                <option value="{{ $key }}">{{ $status }}</option>
                            @endforeach
                        </select>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" wire:click="toggleDeleteModal">Close</button>
                    <button type="button" class="btn btn-danger text-white" wire:click="deleteOrder">Delete</button>
                </div>
            </div>
        </div>
    </div>
</div>
