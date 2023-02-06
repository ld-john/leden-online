<?php

namespace App\Http\Livewire\FitOptions;

use App\Models\Company;
use App\Models\FitOption;
use App\Models\VehicleModel;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class EditFitOption extends Component
{
    public $fitOption;
    public $option_name;
    public $model;
    public $model_year;
    public $dealer;
    public $modalShow = false;
    public $price;
    public $dealers;
    public $vehicle_models;

    public function mount(FitOption $fitOption)
    {
        $this->fitOption = $fitOption;
        $this->option_name = $fitOption->option_name;
        $this->model = $fitOption->model;
        $this->model_year = $fitOption->model_year;
        $this->dealer = $fitOption->dealer;
        $this->price = $fitOption->option_price;
        $this->dealers = Company::where('company_type', 'dealer')->get();
        $this->vehicle_models = VehicleModel::orderBy('name')->get();
    }

    public function toggleEditModal()
    {
        $this->modalShow = !$this->modalShow;
    }

    public function render(): Factory|View|Application
    {
        return view('livewire.fit-options.edit-fit-option');
    }

    public function saveFitOption()
    {
        $this->fitOption->option_name = $this->option_name;
        $this->fitOption->model = $this->model;
        $this->fitOption->model_year = $this->model_year;
        $this->fitOption->dealer_id = $this->dealer;
        $this->fitOption->option_price = $this->price;
        $this->fitOption->save();
        notify()->success('Fit Option Edited Successfully');
        return redirect(request()->header('Referer'));
    }
}
