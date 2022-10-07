<div>
    @if( count($vehicle->factoryFitOptions()) >= 1)
        <button class="btn btn-primary" wire:click="toggleModal()">Yes <span class="badge bg-light text-dark">{{ count($vehicle->factoryFitOptions()) }}</span></button>
    @else
        No
    @endif
    <div class="modal @if($modalShow) show @endif">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Factory Fit Options for {{ $vehicle->id }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" wire:click="toggleModal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <ul>
                        @foreach($vehicle->factoryFitOptions() as $option)
                            <li>{{ $option->factoryOptionName }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>

        </div>
    </div>
</div>
