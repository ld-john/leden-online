<?php

namespace App\Http\Livewire\FitOptions;

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
    public $paginate = 10;


    public function mount($fitType) {
        $this->fitType = $fitType;
    }

    public function render()
    {
        return view('livewire.fit-options.fit-options-editor', ['fitOptions' => FitOption::where('option_type', $this->fitType)->latest()->paginate($this->paginate)]);
    }

}
