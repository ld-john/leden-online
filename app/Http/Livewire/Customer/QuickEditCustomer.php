<?php

namespace App\Http\Livewire\Customer;

use App\Livewire\Forms\CustomerForm;
use App\Models\Customer;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class QuickEditCustomer extends Component
{
    public CustomerForm $customerForm;
    public Customer $customer;

    public function mount(Customer $customer): void
    {
        $this->customerForm->setCustomer($customer);
        $this->customer = $customer;
    }

    public function saveCustomer($redirect = false)
    {
        $this->customerForm->save();

        if ($redirect) {
            return $this->redirect(route('customer.index'));
        }
    }

    public function render(): View|Factory|\Illuminate\View\View
    {
        return view('livewire.customer.quick-edit-customer');
    }
}
