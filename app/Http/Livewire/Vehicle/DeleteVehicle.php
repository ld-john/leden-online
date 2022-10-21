<?php

namespace App\Http\Livewire\Vehicle;

use App\Models\Vehicle;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class DeleteVehicle extends Component
{
    public $vehicle;
    public $modalShow = false;

    public function mount(Vehicle $vehicle)
    {
        $this->vehicle = $vehicle;
    }

    public function render(): Factory|View|Application
    {
        return view('livewire.vehicle.delete-vehicle');
    }

    public function toggleDeleteModal()
    {
        $this->modalShow = !$this->modalShow;
    }
    public function deleteVehicle()
    {
        Vehicle::destroy($this->vehicle->id);
        notify()->success(
            'Vehicle was deleted successfully',
            'Vehicle Deleted',
        );
        return redirect()->route('pipeline');
    }
}
