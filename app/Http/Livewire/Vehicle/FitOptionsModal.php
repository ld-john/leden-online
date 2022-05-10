<?php

namespace App\Http\Livewire\Vehicle;

use App\Vehicle;
use Livewire\Component;

class FitOptionsModal extends Component
{
    public $vehicle;
    public $modalShow;

    public function mount(Vehicle $vehicle) {
        $this->vehicle = $vehicle;
    }
    public function toggleModal()
    {
        $this->modalShow = !$this->modalShow;
    }

    public function render()
    {
        return view('livewire.vehicle.fit-options-modal');
    }
}
