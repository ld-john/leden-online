<?php

namespace App\Http\Livewire;

use App\Company;
use App\Vehicle;
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
        $this->brokers = Company::where('company_type', 'broker')->orderBy('company_name', 'asc')->get();
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
            'broker_id' => $this->target
        ]);
        return redirect(request()->header('Referer'));
    }

    public function render()
    {
        return view('livewire.ring-fence-modal');
    }
}
