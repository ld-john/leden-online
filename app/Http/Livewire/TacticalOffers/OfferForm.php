<?php

namespace App\Http\Livewire\TacticalOffers;

use App\Models\Manufacturer;
use App\Models\TacticalOffer;
use App\Models\Vehicle;
use App\Models\VehicleModel;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use LaravelIdea\Helper\App\Models\_IH_Vehicle_C;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class OfferForm extends Component
{
    use WithPagination;

    public $offer;
    public $offer_name;
    public $offer_value;
    public $offer_start_date;
    public $offer_end_date;
    public $make;
    public $model;
    public $search = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function mount(): void
    {
        if (!isset($this->offer)){
            return;
        }
        $this->offer_name = $this->offer->name;
        $this->offer_value = $this->offer->value;
        $this->make = $this->offer->manufacturer_id;
        $this->model = $this->offer->applicable_model;
        $this->offer_start_date = $this->offer->start_date;
        $this->offer_end_date = $this->offer->end_date;
    }
    public function offerFormSubmit()
    {
        if (!isset($this->offer) || !$this->offer->exists){
            $this->offer = new TacticalOffer();
        }

        $this->offer->fill([
            'name' => $this->offer_name,
            'value' => $this->offer_value,
            'manufacturer_id' => $this->make,
            'applicable_model' => $this->model,
            'start_date' => $this->offer_start_date,
            'end_date' => $this->offer_end_date,
        ]);

        $this->offer->save();


        notify()->success('Offer Updated Successfully');

        return redirect()->to(route('offers.index'));

    }
    public function render(): Factory|View|\Illuminate\View\View
    {
        return view('livewire.tactical-offers.offer-form', [
            'manufacturers' => Manufacturer::orderBy('name')->get()->keyBy('id'),
            'vehicle_models' => $this->make ?
                VehicleModel::where('manufacturer_id', $this->make)->orderBy('name')->get() :
                collect(),
        ]);
    }
}
