<?php

namespace App\Http\Livewire\Customer;

use App\Models\Company;
use App\Models\DealerDiscount;
use App\Models\VehicleModel;
use Livewire\Component;

class DealerDiscountField extends Component
{
    public $dealer;
    public $model;
    public $discount;

    public function mount(Company $dealer, VehicleModel $vehicleModel)
    {
        $this->dealer = $dealer;
        $this->model = $vehicleModel;
        $this->discount =
            DealerDiscount::where('dealer_id', '=', $dealer->id)
                ->where('model_id', '=', $vehicleModel->id)
                ->first()?->discount ?? '0';
    }

    public function saveDiscount()
    {
        DealerDiscount::updateOrCreate(
            [
                'model_id' => $this->model->id,
                'dealer_id' => $this->dealer->id,
            ],
            [
                'discount' => $this->discount,
            ],
        );
        notify()->success('Dealer Discount Edited Successfully');
        return redirect(route('company.edit', $this->dealer->id));
    }

    public function clearDiscount()
    {
        $discount = DealerDiscount::where('dealer_id', '=', $this->dealer->id)
            ->where('model_id', '=', $this->model->id)
            ->first();
        $discount->delete();
        notify()->success('Dealer Discount Cleared Successfully');
        return redirect(route('company.edit', $this->dealer->id));
    }

    public function render()
    {
        return view('livewire.customer.dealer-discount-field');
    }
}
