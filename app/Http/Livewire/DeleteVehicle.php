<?php

namespace App\Http\Livewire;

use App\Vehicle;
use Illuminate\Http\RedirectResponse;
use Livewire\Component;

class DeleteVehicle extends Component
{
    public $vehicle;
    public $modalShow = false;

    public function mount(Vehicle $vehicle)
    {
        $this->vehicle = $vehicle;
    }

    public function render()
    {
        return view('livewire.delete-vehicle');
    }

    public function toggleDeleteModal()
    {
        $this->modalShow = !$this->modalShow;
    }
    public function deleteVehicle()
    {
        Vehicle::destroy($this->vehicle->id);
        return redirect()->route('pipeline');
    }
}
