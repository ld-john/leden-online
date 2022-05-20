<?php

namespace App\Http\Livewire\FitOptions;

use App\FitOption;
use Livewire\Component;

class DeleteFitOption extends Component
{
    public $fitOption;
    public bool $modalShow = false;

    public function mount(FitOption $fitOption)
    {
        $this->fitOption = $fitOption;
    }

    public function toggleDeleteModal()
    {
        $this->modalShow = !$this->modalShow;
    }

    public function render()
    {
        return view('livewire.fit-options.delete-fit-option');
    }
}
