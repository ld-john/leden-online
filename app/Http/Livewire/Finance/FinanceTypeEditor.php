<?php

namespace App\Http\Livewire\Finance;

use App\Models\Finance\FinanceType;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class FinanceTypeEditor extends Component
{
    public $metadata;
    public $new_name;
    public $edit;
    public $edit_name;

    public function mount()
    {
        $this->metadata = \App\Models\Finance\FinanceType::all();
    }

    public function newFinanceType()
    {
        $financeType = new FinanceType();
        $financeType->option = $this->new_name;
        $financeType->save();
        Notify()->success('Finance Type added successfully');
        return redirect(route('finance.finance-type.index'));
    }

    public function showEditModal(\App\Models\Finance\FinanceType $type)
    {
        $this->edit = $type;
        $this->edit_name = $type->option;
    }

    public function delete(\App\Models\Finance\FinanceType $type)
    {
        $type->delete();
        Notify()->success('Finance Type deleted successfully');
        return redirect(route('finance.finance-type.index'));
    }

    public function saveFinanceType()
    {
        $financeType = $this->edit;
        $financeType->update([
            'option' => $this->edit_name,
        ]);
        Notify()->success('Finance Type Edited Successfully');
        return redirect(route('finance.finance-type.index'));
    }

    public function render(): Factory|View|Application
    {
        return view('livewire.finance.finance-type-editor');
    }
}
