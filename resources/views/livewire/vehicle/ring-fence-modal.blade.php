<div>
    <i wire:click="toggleModal()" class="fa-solid fa-car"></i>
    <div class="modal @if($ringFenceModal) show @endif">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Ring Fence {{ $vehicle->id }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" wire:click="toggleModal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <label for="target">Who Should this car be ring-fenced for?</label>
                    <select name=target" id="target" wire:model="target" class="form-control mb-4">
                        @foreach($brokers as $broker)
                            <option value="{{ $broker->id }}">{{$broker->company_name}}</option>
                        @endforeach
                    </select>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" wire:click="toggleModal">Close</button>
                        <button type="button" class="btn btn-primary" wire:click="ringFenceStock">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
