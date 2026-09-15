<?php

namespace App\Http\Livewire\Customer;

use App\Livewire\Forms\CustomerForm;
use App\Models\Customer;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class QuickEditCustomer extends Component
{
    public CustomerForm $customer;

    public function mount(Customer $customer): void
    {
        $this->customer->setCustomer($customer);
    }

    public function saveCustomer(): void
    {
        $this->customer->save();
    }

    public function render(): View|Factory|\Illuminate\View\View
    {
        return view('livewire.customer.quick-edit-customer');
    }
}
