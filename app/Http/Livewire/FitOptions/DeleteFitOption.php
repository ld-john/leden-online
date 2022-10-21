<?php

namespace App\Http\Livewire\FitOptions;

use App\Models\FitOption;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
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

    public function deleteFitOption(): Redirector|Application|RedirectResponse
    {
        $this->fitOption->delete();
        session()->flash('message', 'Fit Option Deleted Successfully');
        return redirect(request()->header('Referer'));
    }

    public function render()
    {
        return view('livewire.fit-options.delete-fit-option');
    }
}
