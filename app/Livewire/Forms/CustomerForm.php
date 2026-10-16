<?php

namespace App\Livewire\Forms;

use App\Models\Customer;
use Illuminate\Pagination\LengthAwarePaginator;
use JetBrains\PhpStorm\NoReturn;
use Livewire\Form;

class CustomerForm extends Form
{
    public ?Customer $customer = null;
    public string $name = '';
    public ?int $id = null;
    public ?string $address_1 = null;
    public ?string $address_2 = null;
    public ?string $town = null;
    public ?string $city = null;
    public ?string $county = null;
    public ?string $postcode = null;
    public ?string $phone_number = null;

    public function rules(): array
    {
        return [
            'id' => ['nullable', 'exists:customers,id'],
            'phone_number' => ['nullable', 'numeric'],
        ];
    }

    public function messages(): array
    {
        return [
            'phone_number.numeric' => 'Please enter a valid phone number'
        ];
    }


    public function setCustomer(?Customer $customer) : void
    {
        if (!$customer) {
            return;
        }
        $this->customer = $customer;
        $this->customerDetails($customer);
    }

    public function getSearchResults(): LengthAwarePaginator
    {
        return Customer::when(strlen($this->name) >= 2, function ($query) {
            $query->where('customer_name', 'like', '%'. $this->name .'%');
        })->paginate(5, ['*'], 'customerPage');
    }

    public function clear(): void
    {
        $this->id = null;
    }

    public function save(): ?Customer
    {
        $this->validate();

        // Find the customer if an ID exists and isn't already loaded correctly
        if ($this->id && (!$this->customer || $this->customer->id !== $this->id)) {
            $this->customer = Customer::find($this->id);
        }

        $customerData = [
            'customer_name' => $this->name,
            'address_1' => $this->address_1,
            'address_2' => $this->address_2,
            'town' => $this->town,
            'city' => $this->city,
            'county' => $this->county,
            'postcode' => $this->postcode,
            'phone_number' => $this->phone_number,
        ];

        if ($this->customer && $this->customer->exists) {
            $this->customer->update($customerData);
            return $this->customer;
        }

        // Create the customer and assign it to the form property
        return $this->customer = Customer::create($customerData);
    }

    /**
     * @param Customer|null $customer
     * @return void
     */
    public function customerDetails(?Customer $customer): void
    {
        $this->id = $customer->id;
        $this->name = $customer->customer_name;
        $this->address_1 = $customer->address_1;
        $this->address_2 = $customer->address_2;
        $this->town = $customer->town;
        $this->city = $customer->city;
        $this->county = $customer->county;
        $this->postcode = $customer->postcode;
        $this->phone_number = $customer->phone_number;
    }
}
