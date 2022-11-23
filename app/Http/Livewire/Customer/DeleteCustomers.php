<?php

namespace App\Http\Livewire\Customer;

use App\Models\Customer;
use App\Models\Order;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class DeleteCustomers extends Component
{
    public $modalShow = false;
    public $customer;
    public $orders;

    public function mount(Customer $customer)
    {
        $this->customer = $customer;
        $this->orders = Order::where('customer_id', '=', $customer->id)
            ->with('vehicle')
            ->with('vehicle.manufacturer')
            ->get();
    }

    public function toggleDeleteModal()
    {
        $this->modalShow = !$this->modalShow;
    }

    public function render(): Factory|View|Application
    {
        return view('livewire.customer.delete-customers');
    }

    public function deleteCustomer()
    {
        Customer::destroy($this->customer->id);
        notify()->success('Customer Deleted Successfully', 'Customer Deleted');
        return redirect(request()->header('Referer'));
    }
}
