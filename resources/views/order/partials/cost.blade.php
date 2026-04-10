{{-- List Price --}}
<x-inputs.currency label="List Price" name="list_price" />
{{-- Metallic Paint --}}
<x-inputs.currency label="Metallic Paint" name="metallic_paint" />
{{-- Dealer Discount --}}
<x-inputs.percentage
        label="Dealer Discount"
        name="dealer_discount"
        override-name="dealer_discount_override"
        :is-overridden="$dealer_discount_override"
        wire:model.blur="dealer_discount"
/>
{{-- Manufacturer Discount --}}
<x-inputs.percentage
        label="Manufacturer Discount"
        name="manufacturer_discount"
        wire:model.blur="manufacturer_discount"
/>
{{-- Total Discount --}}
<x-inputs.percentage
        label="Total Discount"
        name="total_discount"
        :value="$this->invoiceData['total_discount_percent']"
        readonly
/>

{{-- Metallic Paint Discount --}}
<x-inputs.percentage
    label="Metallic Paint Discount"
    name="metallic_paint_discount"
    :value="$this->invoiceData['metallic_discount_percent']"
    readonly
/>

{{-- Leden Flat Discount --}}
<x-inputs.currency label="Leden Flat Discount" name="leden_discount" />

{{-- Manufacturer Delivery Cost --}}
<x-inputs.currency label="Manufacturer Delivery Cost" name="manufacturer_delivery_cost" />

{{-- 1st Reg Fee --}}
<x-inputs.currency label="1st Reg Fee" name="first_reg_fee" />

{{-- RFL --}}
<x-inputs.currency label="RFL" name="rfl_cost" />

{{-- Onward Delivery --}}
<x-inputs.currency label="Onward Delivery" name="onward_delivery" />

{{-- Available Offers --}}
@if ($this->availableOffers->isNotEmpty())
    <div class="mb-3 p-3 border rounded bg-light">
        <label class="form-label d-block fw-bold">Available Tactical Offers</label>
        @foreach($this->availableOffers as $offer)
            <div class="form-check border-bottom py-2">
                <input
                        class="form-check-input"
                        type="checkbox"
                        wire:model.live="selectedOffers"
                        value="{{ $offer->id }}"
                        id="offer-{{ $offer->id }}"
                        wire:key="checkbox-{{ $offer->id }}"
                >
                <label class="form-check-label d-flex justify-content-between w-100" for="offer-{{ $offer->id }}">
                    <span>{{ $offer->name }}</span>
                    <span class="text-success fw-bold">- £{{ number_format($offer->value, 2) }}</span>
                </label>
            </div>
        @endforeach

        <div class="form-check py-2">
            <input class="form-check-input"
                   type="checkbox"
                   value="none"
                   wire:model.live="selectedOffers"
                   id="offer-none"
                   wire:key="checkbox-none"
            >
            <label class="form-check-label text-muted" for="offer-none">
                No offer applied
            </label>
        </div>
    </div>
@endif

{{-- Invoice Funder For --}}
<x-inputs.currency label="Invoice Funder For" name="invoice_funder_for" />

{{-- Estimated Invoice Value --}}
<x-inputs.currency-readonly label="Estimated Invoice Value" name="invoice_value" :value="$this->invoiceData['invoice_value']" />

{{-- Show Discount Applied --}}
<x-inputs.boolean label="Show Discount Applied" name="show_discount" />

{{-- Show as Offer --}}
<x-inputs.boolean label="Show as Offer" name="show_offer" />

{{-- Hide From Broker --}}
<x-inputs.boolean label="Hide From Broker List" name="hide_from_broker" />

{{-- Hide From Dealer --}}
<x-inputs.boolean label="Hide From Dealer List" name="hide_from_dealer" />
