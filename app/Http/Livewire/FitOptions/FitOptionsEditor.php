<?php

namespace App\Http\Livewire\FitOptions;

use App\FitOption;
use Livewire\Component;

class FitOptionsEditor extends Component
{
    public $fitType;
    public $paginate = 10;

    public function mount($fitType) {
        $this->fitType = $fitType;
    }

    public function render()
    {
        $fitOptions = FitOption::where('option_type', $this->fitType)->latest()->paginate($this->paginate);
        return view('livewire.fit-options.fit-options-editor', ['fitOptions' => $fitOptions]);
    }
}
