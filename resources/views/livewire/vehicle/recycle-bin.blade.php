<div class="table-responsive">
    <table class="table table-bordered table-striped">
        <thead>
        <tr class="blue-background text-white">
            <th>ID</th>
            <th>Make</th>
            <th>Model</th>
            <th>Derivative</th>
            <th>Engine</th>
            <th>Colour</th>
            <th>Type</th>
            <th>Chassis</th>
            <th>Registration</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        <tr class="bg-light">
            <th class="p-1">
                <input wire:model.blur="searchID" type="text" class="form-control"
                       placeholder="Search ID">
            </th>
            <th class="p-1">
                <input wire:model.blur="searchMake" type="text" class="form-control"
                       placeholder="Search Make">
            </th>
            <th class="p-1">
                <input wire:model.blur="searchModel" type="text" class="form-control"
                       placeholder="Search Model">
            </th>
            <th class="p-1">
                <input wire:model.blur="searchDerivative" type="text" class="form-control"
                       placeholder="Search Derivative">
            </th>
            <th class="p-1">
                <input wire:model.blur="searchEngine" type="text" class="form-control"
                       placeholder="Search Engine">
            </th>
            <th class="p-1">
                <input wire:model.blur="searchColour" type="text" class="form-control"
                       placeholder="Search Colour">
            </th>
            <th class="p-1">
                <input wire:model.blur="searchType" type="text" class="form-control"
                       placeholder="Search Type">
            </th>
            <th class="p-1">
                <input wire:model.blur="searchChassis" type="text" class="form-control"
                       placeholder="Search Chassis">
            </th>
            <th class="p-1">
                <input wire:model.blur="searchRegistration" type="text" class="form-control"
                       placeholder="Search Registration">
            </th>
            <th class="p-1">
                <select wire:model.live="searchStatus" name="status" id="status" class="form-select">
                    <option value="">Select Status</option>
                    @foreach($status as $s)
                        <option value="{{ $s->value }}">
                            {{ $s->label() }}
                        </option>
                    @endforeach
                </select>
            </th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach( $vehicles as $vehicle )
            <tr>
                <td>
                    <strong>{{ $vehicle->id ?? '' }}</strong>
                    @if($vehicle->orbit_number)
                        <br>(Orbit Number: {{ $vehicle->orbit_number }})
                    @endif
                </td>
                <td>{{ $vehicle->manufacturer->name ?? '' }}</td>
                <td>{{ $vehicle->model ?? '' }}</td>
                <td>{{ $vehicle->derivative ?? '' }}</td>
                <td>{{ $vehicle->engine ?? '' }}</td>
                <td>{{ $vehicle->colour ?? '' }}</td>
                <td>{{ $vehicle->type ?? '' }}</td>
                <td>{{ $vehicle->chassis ?? '' }}</td>
                <td>{{ $vehicle->reg ?? '' }}</td>
                <td>{{ $vehicle->vehicle_status->label() }}</td>
                <td>
                    <div class="d-grid grid-cols-2 gap-2">
                        <button
                                wire:click="restore({{$vehicle->id}})"
                                class="btn btn-success"
                                data-toggle="tooltip"
                                title="Restore"
                                wire:loading.attr="disabled"
                        >
                            <i class="fa-solid fa-trash-arrow-up"></i>
                        </button>
                        <button
                                wire:click="forceDelete({{ $vehicle->id }})"
                                wire:confirm="Are you sure you want to permanently delete this vehicle?"
                                wire:loading.attr="disabled"
                                class="btn btn-danger"
                                data-toggle="tooltip"
                                title="PERMANENTLY DELETE"
                        >
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                    </div>
                </td>
            </tr>

        @endforeach
        </tbody>
    </table>
    <div class="d-flex justify-content-center mt-3">
        {{ $vehicles->links() }}
    </div>
</div>
