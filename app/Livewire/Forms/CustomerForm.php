<?php

namespace App\Livewire\Forms;

use App\Models\Customer;
use Cassandra\Custom;
use Illuminate\Pagination\LengthAwarePaginator;
use JetBrains\PhpStorm\NoReturn;
use Livewire\Component;
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
            'id' => ['required', 'exists:customers,id'],
            'phone_number' => ['nullable', 'numeric'],
        ];
    }

    public function messages(): array
    {
        return [
            'id.required' => 'Please select a customer from the list or create a new one.',
            'phone_number.numeric' => 'Please enter a valid phone number'
        ];
    }

    #[NoReturn]
    public function updatedId($value): void
    {
        $customer = Customer::where('id', $value)->first();
        $this->customerDetails($customer);
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

        if ($this->customer && $this->customer->exists) {
            $this->customer->update([
                'customer_name' => $this->name,
                'address_1' => $this->address_1,
                'address_2' => $this->address_2,
                'town' => $this->town,
                'city' => $this->city,
                'county' => $this->county,
                'postcode' => $this->postcode,
                'phone_number' => $this->phone_number,
            ]);

            return $this->customer;
        }

        return Customer::create([
            'customer_name' => $this->name,
            'address_1' => $this->address_1,
            'address_2' => $this->address_2,
            'town' => $this->town,
            'city' => $this->city,
            'county' => $this->county,
            'postcode' => $this->postcode,
            'phone_number' => $this->phone_number,
        ]);
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
