<?php

namespace App\Http\Livewire\Vehicle;

use App\Models\Company;
use App\Models\Vehicle;
use Livewire\Component;

class QuickEditRingfence extends Component
{
    public $vehicle;
    public $brokers;
    public $broker;

    public function mount(Vehicle $vehicle)
    {
        $this->vehicle = $vehicle;
        $this->brokers = Company::where('company_type', 'broker')->get();
        $this->broker = $vehicle->broker_id;
    }

    public function saveBroker()
    {
        $this->vehicle->update(['broker_id' => $this->broker]);

        notify()->success(
            'Ringfenced Broker was updated successfully',
            'Vehicle Updated',
        );
        return $this->redirect(route('ring_fenced_stock'));
    }

    public function render()
    {
        return view('livewire.vehicle.quick-edit-ringfence');
    }
}
