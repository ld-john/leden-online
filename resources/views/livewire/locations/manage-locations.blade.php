<div>
    @if($editID)
        <h4>Edit Location</h4>
    @else
        <h4>Create New Location</h4>
    @endif
    <div class="row align-items-end">
        <div class="col-md-5">
            <label class="form-label" for="location">Location</label>
            <input wire:model.live="location" type="text" class="form-control" name="location" id="location">
            @error('location')
            <div class="alert alert-danger mt-2">{{ $errors->first('location')}}</div>
            @enderror
        </div>
        <div class="col-md-5">
            <label class="form-label" for="status">Status</label>
            <select wire:model.live="status" name="status" id="status" class="form-select">
                <option value="">--</option>
                @foreach($statuses as $key => $status)
                    <option value="{{$key}}">{{ $status }}</option>
                @endforeach
            </select>
            @error('status')
            <div class="alert alert-danger mt-2">{{ $errors->first('status')}}</div>
            @enderror
        </div>
        <div class="col-md-2">
            @if($editID)
                <button wire:click="saveEditedLocation" class="btn btn-secondary">Save Edit</button>
            @else
                <button wire:click="saveLocation" class="btn btn-primary mt-auto">Save New Location</button>
            @endif
        </div>
    </div>
    <h4 class="mt-6">Locations</h4>
    <p>On the Ford Report CSV Import, any vehicle with a location not listed on this table will be marked with a status
        of Factory Order</p>
    <table class="table table-bordered ">
        <tr class="blue-background text-white">
            <th>Location</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        @forelse($locations as $location)
            <tr>
                <td>{{ $location->location }}</td>
                <td>{{ \App\Models\Vehicle::statusMatch(intval($location->status))  }}</td>
                <td class="text-center" style="width: 120px">
                    <div class="btn-group">
                        <button wire:click="editLocation({{ $location }})" class="btn btn-warning"><i
                                    class="fa-solid fa-pen-to-square"></i></button>
                        <button wire:click="deleteLocation({{ $location }})" class="btn btn-danger"><i
                                    class="fa-solid fa-trash"></i></button>
                    </div>
                </td>
            </tr>
        @empty
        @endforelse
    </table>
</div>
