<?php

namespace App\Http\Livewire\Customer;

use App\Models\Company;
use App\Models\VehicleModel;
use Livewire\Component;

class DealerDiscountEditor extends Component
{
    public $dealer;
    public $models;

    public function mount(Company $dealer)
    {
        $this->dealer = $dealer;
        $this->models = VehicleModel::whereHas('manufacturer', function ($q) {
            $q->where('slug', '=', 'ford');
        })->get();
    }

    public function render()
    {
        return view('livewire.customer.dealer-discount-editor');
    }
}
