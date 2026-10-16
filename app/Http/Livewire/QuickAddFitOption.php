<?php

namespace App\Http\Livewire;

use App\Models\Company;
use App\Models\FitOption;
use App\Models\VehicleModel;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\Component;

class QuickAddFitOption extends Component
{
    public bool $showModal = false;

    // Form attributes
    public string $fitType = 'factory'; // 'factory' or 'dealer'
    public ?int $vehicle_model_id = null;
    public string $option_name = '';
    public string $model_year = '';
    public ?int $dealer_id = null;
    public string $price = '';

    // Collections for dropdowns
    public $vehicle_models = [];
    public $dealers = [];

    public function mount(string $fitType = 'factory') :void
    {
        $this->fitType = $fitType;
        $this->vehicle_models = VehicleModel::orderBy('name')->get();
        if ($this->fitType === 'dealer') {
            $this->dealers = Company::orderBy('company_name')->where('company_type', 'dealer')->get();
        }
    }

    #[On('open-quick-add-fit-option')]
    public function openModal(?int $modelId = null, ?string $modelYear = null): void
    {
        $this->resetValidation();
        $this->reset(['option_name', 'price']);
        if ($modelId) {
            $this->vehicle_model_id = $modelId;
        }

        if ($modelYear) {
            $this->model_year = $modelYear;
        }
        $this->showModal = true;
    }

    public function save(bool $addAnother = false) :void
    {
        $rules = [
            'vehicle_model_id' => 'required|exists:vehicle_models,id',
            'option_name' => 'required|string|max:255',
            'model_year' => 'nullable|string|max:10',
            'price' => 'required|numeric|min:0'
        ];

        if ($this->fitType === 'dealer') {
            $rules['dealer_id'] = 'required|exists:companies,id';
        }

        $this->validate($rules);

        FitOption::create([
            'model' => $this->vehicle_model_id,
            'option_name' => trim($this->option_name),
            'model_year' => trim($this->model_year),
            'option_price' => $this->price,
            'dealer_id' => $this->fitType === 'dealer' ? $this->dealer_id : null,
            'option_type' => $this->fitType
        ]);

        $this->dispatch('fit-option-created');

        session()->flash('success_msg', 'Fit option added successfully');

        if ($addAnother) {
            // Keep model, year, and dealer intact so the user can quickly type the next option name & price
            $this->reset(['option_name', 'price']);
            $this->resetValidation();
        } else {
            $this->showModal = false;
        }
    }

    public function render()
    {
        return view('livewire.quick-add-fit-option');
    }
}
