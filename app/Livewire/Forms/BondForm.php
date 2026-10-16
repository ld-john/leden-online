<?php

namespace App\Livewire\Forms;

use App\Models\Bond;
use Livewire\Form;

class BondForm extends Form
{

    public ?Bond $bond = null;
    public ?float $bond_value = null;
    #[Cast('date:Y-m-d')]
    public ?string $payment_date = null;
    public ?float $refund_value = null;
    public ?string $refund_date = null;

    public function rules(): array
    {
        return [
            'bond_value' => 'required_with:payment_date|nullable|numeric',
            'payment_date' => 'required_with:bond_value|nullable|date',
            'refund_value' => 'nullable|numeric',
            'refund_date' => 'nullable|date'
        ];

    }

    public function messages(): array
    {
        return [
            'bond_value.required_with' => 'Bond Value is required if there is a Payment Date set',
            'bond_value.numeric' => 'Invalid Bond Value',
            'payment_date.required_with' => 'Payment Date is required if there is a Bond Value set',
            'refund_date.date' => 'Invalid Date format for Refund Date',
            'refund_value.numeric' => 'Invalid Refund Value',
        ];

    }

    public function setBond(?Bond $bond): void
    {
        if (!$bond) {
            return;
        }

        $this->bond = $bond;
        $this->bond_value = $bond->bond_value;
        $this->payment_date = $bond->payment_date?->format('Y-m-d') ?? '';
        $this->refund_value = $bond->refund_value;
        $this->refund_date = $bond->refund_date?->format('Y-m-d') ?? '';
    }

    /**
     * Sanitise empty or invalid date strings to null.
     */
    private function sanitiseDate(?string $date): ?string
    {
        if (empty($date) || $date === '-0001-11-30' || $date === '0000-00-00') {
            return null;
        }

        return $date;
    }

    public function save() : ?Bond
    {
        $refund_date = $this->sanitiseDate($this->refund_date);
        $payment_date = $this->sanitiseDate($this->payment_date);

        if (empty($this->bond_value) && empty($payment_date)) {
            return null;
        }

        $this->validate();


        if ($this->bond && $this->bond->exists) {
            $this->bond->update([
                'bond_value' => $this->bond_value,
                'payment_date' => $payment_date,
                'refund_value' => $this->refund_value,
                'refund_date' => $refund_date
            ]);
            return $this->bond;
        }

        return Bond::create([
            'bond_value' => $this->bond_value,
            'payment_date' => $payment_date,
            'refund_value' => $this->refund_value,
            'refund_date' => $refund_date
        ]);
    }
}
