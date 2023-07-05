<?php

namespace App\Http\Livewire\FitOptions;

use App\Company;
use App\FitOption;
use Livewire\Component;

class NewFitOption extends Component
{
    public $option_name;
    public $model;
    public $model_year;
    public $dealer;
    public $price;
    public $fitType;
    public $dealers;
    protected $rules = array(
        'option_name' => 'required',
        'model' => 'required',
        'model_year' => 'required',
        'price' => 'required'
    );

    public function mount($fitType) {
        $this->fitType = $fitType;
        $this->dealers = Company::where('company_type', 'dealer')->get();
    }

    public function render()
    {
        return view('livewire.fit-options.new-fit-option');
    }

    public function addNewOption() {
        $this->validate();
        $option = new FitOption();
        $option->option_name = $this->option_name;
        $option->model = $this->model;
        $option->model_year = $this->model_year;
        $option->dealer_id = $this->dealer;
        $option->option_price = $this->price;
        $option->option_type = $this->fitType;
        $option->save();
        return redirect(request()->header('Referer'));
    }

}
