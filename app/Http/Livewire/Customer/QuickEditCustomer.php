<?php

namespace App\Http\Livewire\Customer;

use App\Customer;
use Livewire\Component;

class QuickEditCustomer extends Component
{
    public $customer;
    public $customer_name;
    public $address_1;
    public $address_2;
    public $town;
    public $city;
    public $county;
    public $postcode;
    public $phone_number;
    protected $rules = array(
        'customer_name' => 'required',
        'address_1' => 'required',
        'phone_number' => 'required|numeric'
    );

    public function mount(Customer $customer)
    {
        $this->customer = $customer;
        $this->customer_name = $customer->customer_name;
        $this->address_1 = $customer->address_1;
        $this->address_2 = $customer->address_2;
        $this->town = $customer->town;
        $this->city = $customer->city;
        $this->county = $customer->county;
        $this->postcode = $customer->postcode;
        $this->phone_number = $customer->phone_number;
    }

    public function saveCustomer()
    {
        $customer = $this->customer;
        $customer->update([
           'customer_name' => $this->customer_name,
           'address_1' => $this->address_1,
           'address_2' => $this->address_2,
           'town' => $this->town,
           'city' => $this->city,
           'county' => $this->county,
           'postcode' => $this->postcode,
           'phone_number' => $this->phone_number,
        ]);
    }

    public function render()
    {
        return view('livewire.customer.quick-edit-customer');
    }
}
