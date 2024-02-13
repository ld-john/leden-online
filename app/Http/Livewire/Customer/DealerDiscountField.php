<?php

namespace App\Http\Livewire\Customer;

use App\Models\Company;
use App\Models\DealerDiscount;
use App\Models\VehicleModel;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Livewire\Component;

class DealerDiscountField extends Component
{
    public $dealer;
    public $model;
    public $discount;
    public $paint_discount;

    public function mount(Company $dealer, VehicleModel $vehicleModel): void
    {
        $this->dealer = $dealer;
        $this->model = $vehicleModel;
        $this->discount =
            DealerDiscount::where('dealer_id', '=', $dealer->id)
                ->where('model_id', '=', $vehicleModel->id)
                ->first()?->discount ?? 0;
        $this->paint_discount =
            DealerDiscount::where('dealer_id', '=', $dealer->id)
                ->where('model_id', '=', $vehicleModel->id)
                ->first()->paint_discount ?? 0;
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
                'paint_discount' => $this->paint_discount,
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

    public function render(): View|Application|Factory|\Illuminate\View\View|\Illuminate\Contracts\Foundation\Application
    {
        return view('livewire.customer.dealer-discount-field');
    }
}
