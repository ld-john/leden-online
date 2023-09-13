<div>
    @if($vehicle)
        @if($vehicle->websiteLocation()['location'] === 'Ring-fenced Stock' || $vehicle->websiteLocation()['location'] === 'Leden Stock')
            <a
                    wire:click="restoreOrderAndVehicle"
                    class="btn btn-success"
                    data-toggle="tooltip"
                    title="Restore Order - Vehicle is still available"
            >
                <i class="fa-solid fa-trash-arrow-up"></i>
            </a>
        @else
            <a
                    wire:click="restoreOrderNewVehicle"
                    class="btn btn-warning"
                    data-toggle="tooltip"
                    title="Restore Order - Create a new copy of the Vehicle"
            >
                <i class="fa-solid fa-trash-arrow-up"></i>
            </a>
        @endif
    @endif
</div>