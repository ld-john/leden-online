<?php

namespace App\Http\Livewire\Vehicle;

use App\Models\Vehicle;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class FitOptionsModal extends Component
{
    public $vehicle;
    public $modalShow;

    public function mount(Vehicle $vehicle)
    {
        $this->vehicle = $vehicle;
    }
    public function toggleModal()
    {
        $this->modalShow = !$this->modalShow;
    }

    public function render(): Factory|View|Application
    {
        return view('livewire.vehicle.fit-options-modal');
    }
}
