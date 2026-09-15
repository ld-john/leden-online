<?php

namespace App\Livewire\Forms;

use App\Models\DealerDiscount;
use App\Models\TacticalOffer;
use Illuminate\Support\Collection;
use Livewire\Form;

class CostForm extends Form
{
    public ?float $list_price = 0.0;
    public ?float $metallic_paint = 0.0;
    public ?float $dealer_discount = 0.0;
    public bool $dealer_discount_override = false;
    public ?float $manufacturer_discount = 0.0;
    public ?float $leden_discount = 0.0;
    public ?float $manufacturer_delivery_cost = 0.0;
    public ?float $first_reg_fee = 0.0;
    public ?float $rfl_cost = 0.0;
    public ?float $onward_delivery = 0.0;
    public ?float $invoice_funder_for = 0.0;
    public ?float $grant = 0.0;

    // Tactical Offers Selection
    public array $selectedOffers = [];

    // Display Toggles
    public bool $show_discount = false;
    public bool $show_offer = false;
    public bool $hide_from_broker = false;
    public bool $hide_from_dealer = false;

    public function rules(): array
    {
        return [
            'list_price' => ['nullable', 'numeric'],
            'dealer_discount' => ['nullable', 'numeric'],
            'manufacturer_discount' => ['nullable', 'numeric'],
        ];
    }

    public function messages(): array
    {
        return [];
    }

    public function invoiceData() :array
    {
        $listPrice = (float) $this->list_price;
        $metallic = (float) $this->metallic_paint;

        $totalDiscountPercent = (float) $this->dealer_discount + (float) $this->manufacturer_discount;
        $metallicDiscountPercent = (float) $this->manufacturer_discount;

        $offersDeduction = TacticalOffer::whereIn('id', array_filter($this->selectedOffers))
            ->sum('value');

        $discountAmount = ($listPrice * ($totalDiscountPercent / 100)) + (float) $this->leden_discount;
        $invoiceValue = max(0, ($listPrice + $metallic + (float) $this->manufacturer_delivery_cost) - $discountAmount - $offersDeduction - $this->grant);

        return [
            'total_discount_percent' => number_format($totalDiscountPercent, 2),
            'metallic_discount_percent' => number_format($metallicDiscountPercent, 2),
            'invoice_value' => number_format($invoiceValue, 2),
        ];
    }

    public function availableOffers(): Collection
    {
        // Access vehicle make and model from the parent Livewire component or vehicle form object
        $make = $this->component->vehicle->make ?? $this->component->make ?? null;
        $model = $this->component->vehicle->model ?? $this->component->model ?? null;

        if (! $make) {
            return collect();
        }

        return TacticalOffer::query()
            ->active()
            ->where('manufacturer_id', $make)
            ->where(function ($query) use ($model) {
                $query->where('applicable_model', $model)
                    ->orWhereNull('applicable_model');
            })
            ->get();
    }

    public function getDealerDiscount(?int $modelId, ?int $dealerId): void {

        if ($this->dealer_discount_override) {
            return;
        }

        if (!$modelId || !$dealerId) {
            $this->dealer_discount = 0;
            return;
        }

        $discount = DealerDiscount::query()
            ->where('model_id', $modelId)
            ->where('dealer_id', $dealerId)
            ->value('discount');

        $this->dealer_discount = (float) ($discount ?? 0);


    }
}
