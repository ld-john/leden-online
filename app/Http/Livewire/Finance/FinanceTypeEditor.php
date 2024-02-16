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

    public function mount(): void
    {
        $this->metadata = FinanceType::with('orders')->get();
    }

    public function newFinanceType()
    {
        $financeType = new FinanceType();
        $financeType->option = $this->new_name;
        $financeType->save();
        Notify()->success('Finance Type added successfully', 'Added!');
        return redirect(route('finance.finance-type.index'));
    }

    public function showEditModal(FinanceType $type)
    {
        $this->edit = $type;
        $this->edit_name = $type->option;
    }

    public function delete(FinanceType $type)
    {
        $type->delete();
        Notify()->success('Finance Type deleted successfully', 'Deleted');
        return redirect(route('finance.finance-type.index'));
    }

    public function saveFinanceType()
    {
        $financeType = $this->edit;
        $financeType->update([
            'option' => $this->edit_name,
        ]);
        Notify()->success('Finance Type Edited Successfully', 'Type Edited');
        return redirect(route('finance.finance-type.index'));
    }

    public function render(): Factory|View|Application
    {
        return view('livewire.finance.finance-type-editor');
    }
}
