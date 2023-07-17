<?php

namespace App\Http\Livewire\FitOptions;

use App\Models\Company;
use App\Models\FitOption;
use App\Models\VehicleModel;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
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
    public $showArchive = false;
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

    public function render(): Factory|View|Application
    {
        return view('livewire.fit-options.fit-options-editor', [
            'vehicle_models' => VehicleModel::orderBy('name')->get(),
            'fitOptions' => FitOption::where('option_type', $this->fitType)
                ->when($this->showArchive === false, function ($query) {
                    $query->where('archived_at', null);
                })
                ->with('vehicle_model')
                ->with('vehicles')
                ->with('dealer')
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
                ->when($this->dealer, function ($query) {
                    $query->where('dealer_id', '=', $this->dealer);
                })
                ->when($this->price, function ($query) {
                    $query->where(
                        'option_price',
                        'like',
                        '%' . $this->price . '%',
                    );
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
        notify()->success(
            'New ' . ucfirst($this->fitType) . ' Fit Option Created',
            'Fit Option Created Successfully',
        );
        return redirect(request()->header('Referer'));
    }

    public function archiveFitOption($fit_option)
    {
        $option = FitOption::whereId($fit_option)->first();

        $option->option_name .= ' - ARCHIVED';
        $option->archived_at = now();
        $option->save();

        return redirect(request()->header('Referer'));
    }

    public function unarchiveFitOption($fit_option)
    {
        $option = FitOption::whereId($fit_option)->first();

        $option->option_name = substr($option->option_name, 0, -11);
        $option->archived_at = null;
        $option->save();

        return redirect(request()->header('Referer'));
    }
}
