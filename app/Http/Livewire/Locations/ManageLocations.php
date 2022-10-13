<?php

namespace App\Http\Livewire\Locations;

use App\Location;
use App\Vehicle;
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
        $this->statuses = [
            1 => 'In Stock',
            3 => 'Ready for Delivery',
            5 => 'Awaiting Delivery Confirmation',
            6 => 'Delivery Booked',
            7 => 'Completed Orders',
            10 => 'Europe VHC',
            12 => 'At Converter',
            13 => 'Awaiting Ship',
            11 => 'UK VHC',
            14 => 'Recall',
            15 => 'In Stock (Registered)',
            16 => 'Damaged/Recalled',
        ];
    }

    public function editLocation(Location $location)
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
