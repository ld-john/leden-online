<?php

namespace App\Http\Livewire\FitOptions;

use App\Company;
use App\FitOption;
use Livewire\Component;
use Livewire\WithPagination;

class FitOptionsEditor extends Component
{
    use WithPagination;

    public function getQueryString(): array
    {
        return [];
    }

    protected $paginationTheme = 'bootstrap';

    public $fitType;
    public $option_name;
    public $model;
    public $model_year;
    public $dealer;
    public $price;
    public $dealers;
    protected $rules = [
        'option_name' => 'required',
        'model' => 'required',
        'model_year' => 'required',
        'price' => 'required',
    ];
    public $paginate = 10;

    public function mount($fitType)
    {
        $this->fitType = $fitType;
        $this->dealers = Company::where('company_type', 'dealer')->get();
    }

    public function render()
    {
        return view('livewire.fit-options.fit-options-editor', [
            'fitOptions' => FitOption::where('option_type', $this->fitType)
                ->when($this->option_name, function ($query) {
                    $query->where(
                        'option_name',
                        'like',
                        '%' . $this->option_name . '%',
                    );
                })
                ->when($this->model_year, function ($query) {
                    $query->where(
                        'model_year',
                        'like',
                        '%' . $this->model_year . '%',
                    );
                })
                ->when($this->model, function ($query) {
                    $query->where('model', 'like', '%' . $this->model . '%');
                })
                ->latest()
                ->paginate($this->paginate),
        ]);
    }

    public function addNewOption()
    {
        $this->validate();
        $option = new FitOption();
        $option->option_name = $this->option_name;
        $option->model = $this->model;
        $option->model_year = $this->model_year;
        $option->dealer_id = $this->dealer;
        $option->option_price = $this->price;
        $option->option_type = $this->fitType;
        $option->save();
        session()->flash(
            'message',
            'New ' . ucfirst($this->fitType) . ' Fit Option Created',
        );
        return redirect(request()->header('Referer'));
    }
}
