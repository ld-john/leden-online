<?php

namespace App\Http\Livewire\Vehicle;

use App\Models\Company;
use App\Models\Vehicle;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class RingFenceModal extends Component
{
    public $ringFenceModal = false;
    public $vehicle;
    public $brokers;
    public $target;

    public function mount(Vehicle $vehicle)
    {
        $this->vehicle = $vehicle;
        $this->brokers = Company::where('company_type', 'broker')
            ->orderBy('company_name', 'asc')
            ->get();
    }

    public function toggleModal()
    {
        $this->ringFenceModal = !$this->ringFenceModal;
    }

    public function ringFenceStock()
    {
        $vehicle = $this->vehicle;
        $vehicle->update([
            'ring_fenced_stock' => 1,
            'broker_id' => $this->target,
        ]);
        notify()->success(
            'Vehicle was moved to ring fenced stock',
            'Vehicle Moved',
        );
        return redirect(request()->header('Referer'));
    }

    public function render(): Factory|View|Application
    {
        return view('livewire.vehicle.ring-fence-modal');
    }
}
