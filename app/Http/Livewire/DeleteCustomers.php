<?php

namespace App\Http\Livewire;

use App\Customer;
use App\Invoice;
use App\Order;
use App\Vehicle;
use Livewire\Component;

class DeleteCustomers extends Component
{
    public $modalShow = false;
    public $customer;
    public $orders;


    public function mount(Customer $customer)
    {
        $this->customer = $customer;
        $this->orders = $customer->orders;
    }

    public function toggleDeleteModal()
    {
        $this->modalShow = !$this->modalShow;
    }

    public function render()
    {
        return view('livewire.delete-customers');
    }

    public function deleteCustomer()
    {
        Customer::destroy($this->customer->id);
        return redirect(request()->header('Referer'));
    }
}
