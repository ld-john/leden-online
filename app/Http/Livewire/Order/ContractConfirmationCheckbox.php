<?php

namespace App\Http\Livewire\Order;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ContractConfirmationCheckbox extends Component
{
    public $contract;
    public $order;
    public $userPermissions;

    public function mount(Order $order)
    {
        $this->order = $order;
        $this->contract = $order->contract_confirmation;
        $this->userPermissions = Auth::user()
            ->canPerform->pluck('name')
            ->toArray();
    }

    public function toggleContractConfirmation()
    {
        $this->order->update([
            'contract_confirmation' => !$this->contract,
        ]);
        return $this->redirect(route('order_bank'));
    }

    public function render()
    {
        return view('livewire.order.contract-confirmation-checkbox');
    }
}
