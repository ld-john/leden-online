<div>
    <button
            class="btn btn-warning"
            type="button"
            data-bs-toggle="offcanvas"
            data-bs-target="#offcanvas-{{$vehicle->id}}"
            aria-controls="offcanvasExample">
        <i class="fa-solid fa-pencil"></i>
    </button>
    <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvas-{{$vehicle->id}}" aria-labelledby="offcanvasExampleLabel" wire:ignore.self>
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="offcanvasExampleLabel">Move Broker for Vehicle #{{ $vehicle->id }}</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <div class="form-group row">
                <label for="broker">Select Broker</label>
                <div class="mb-3">
                    <select wire:model="broker" name="broker" id="broker" class="form-select">
                        <option value="">Select Broker</option>
                        @foreach($brokers as $broker)
                            <option value="{{ $broker->id }}">{{ $broker->company_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="my-4">
                <button type="button" class="btn btn-primary" wire:click="saveBroker">Save</button>
            </div>
        </div>
    </div>
</div>
