<?php

namespace App\Http\Livewire\FitOptions;

use App\Company;
use App\FitOption;
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

    public function mount(FitOption $fitOption) {
        $this->fitOption = $fitOption;
        $this->option_name = $fitOption->option_name;
        $this->model = $fitOption->model;
        $this->model_year = $fitOption->model_year;
        $this->dealer = $fitOption->dealer;
        $this->price = $fitOption->option_price;
        $this->dealers = Company::where('company_type', 'dealer')->get();
    }

    public function toggleEditModal()
    {
        $this->modalShow = !$this->modalShow;
    }

    public function render()
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
        return redirect(request()->header('Referer'));
    }
}
