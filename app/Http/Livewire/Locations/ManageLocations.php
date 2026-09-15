<?php

namespace App\Http\Livewire\Locations;

use App\Enums\VehicleStatus;
use App\Models\Location;
use Illuminate\Validation\Rule;
use Livewire\Component;

class ManageLocations extends Component
{
    public $locations;
    public $statuses;
    public $location;
    public $status;
    public $editID;

    public function mount()
    {
        $this->locations = Location::orderBy('location')->get();
        $this->statuses = collect(VehicleStatus::cases())
            ->mapWithKeys(fn($status) => [$status->value => $status->label()])
            ->toArray();
    }

    public function editLocation(Location $location): void
    {
        $this->editID = $location->id;
        $this->location = $location->location;
        $this->status = $location->status;
    }

    public function deleteLocation(Location $location)
    {
        $location->delete();
        notify()->success('Location Deleted Successfully', 'Location Deleted');
        return redirect(request()->header('Referer'));
    }

    protected $rules = [
        'location' => 'required|unique:locations',
        'status' => 'required',
    ];

    public function saveLocation()
    {
        $this->validate();
        Location::create([
            'location' => strtoupper($this->location),
            'status' => $this->status,
        ]);
        notify()->success('Location Added Successfully', 'Location Added');
        return redirect(request()->header('Referer'));
    }

    public function saveEditedLocation()
    {
        $this->validate([
            'location' => [
                'required',
                Rule::unique('locations')->ignore($this->editID),
            ],
            'status' => 'required',
        ]);
        $location = Location::where('id', $this->editID)->first();
        $location->update([
            'location' => strtoupper($this->location),
            'status' => $this->status,
        ]);
        $this->editID = null;
        notify()->success('Location Edited Successfully', 'Location Edited');
        return redirect(request()->header('Referer'));
    }

    public function render()
    {
        return view('livewire.locations.manage-locations');
    }
}
