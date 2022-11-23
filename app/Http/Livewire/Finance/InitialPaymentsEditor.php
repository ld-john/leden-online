<?php

namespace App\Http\Livewire\Finance;

use App\Models\Finance\FinanceType;
use App\Models\Finance\InitialPayment;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class InitialPaymentsEditor extends Component
{
    public $metadata;
    public $new_name;
    public $edit;
    public $edit_name;

    public function mount()
    {
        $this->metadata = InitialPayment::with('orders')->get();
    }

    public function newInitialPayment()
    {
        $initialPayment = new FinanceType();
        $initialPayment->option = $this->new_name;
        $initialPayment->save();
        Notify()->success('Initial Payment added successfully');
        return redirect(route('finance.initial-payment.index'));
    }

    public function showEditModal(InitialPayment $type)
    {
        $this->edit = $type;
        $this->edit_name = $type->option;
    }

    public function delete(\App\Models\Finance\InitialPayment $type)
    {
        $type->delete();
        Notify()->success('Initial Payment deleted successfully');
        return redirect(route('finance.initial-payment.index'));
    }

    public function saveInitialPayment()
    {
        $initialPayment = $this->edit;
        $initialPayment->update([
            'option' => $this->edit_name,
        ]);
        Notify()->success('Initial Payment Edited Successfully');
        return redirect(route('finance.initial-payment.index'));
    }
    public function render(): Factory|View|Application
    {
        return view('livewire.finance.initial-payments-editor');
    }
}
