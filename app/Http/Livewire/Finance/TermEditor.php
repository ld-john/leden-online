<?php

namespace App\Http\Livewire\Finance;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class TermEditor extends Component
{
    public $metadata;
    public $new_name;
    public $edit;
    public $edit_name;

    public function mount()
    {
        $this->metadata = \App\Models\Finance\Term::all();
    }

    public function newTerm()
    {
        $term = new \App\Models\Finance\Term();
        $term->option = $this->new_name;
        $term->save();
        Notify()->success('Term added successfully');
        return redirect(route('finance.term.index'));
    }

    public function showEditModal(\App\Models\Finance\Term $type)
    {
        $this->edit = $type;
        $this->edit_name = $type->option;
    }

    public function delete(\App\Models\Finance\Term $type)
    {
        $type->delete();
        Notify()->success('Term deleted successfully');
        return redirect(route('finance.term.index'));
    }

    public function saveTerm()
    {
        $term = $this->edit;
        $term->update([
            'option' => $this->edit_name,
        ]);
        Notify()->success('Term Edited Successfully');
        return redirect(route('finance.term.index'));
    }

    public function render(): Factory|View|Application
    {
        return view('livewire.finance.term-editor');
    }
}
