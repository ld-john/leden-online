{{-- List Price --}}
<x-inputs.currency label="List Price" name="cost.list_price" />
{{-- Metallic Paint --}}
<x-inputs.currency label="Metallic Paint" name="cost.metallic_paint" />
{{-- Dealer Discount --}}
<x-inputs.percentage
        label="Dealer Discount"
        name="dealer_discount"
        override-name="cost.dealer_discount_override"
        :is-overridden="$cost->dealer_discount_override"
        wire:model.blur="cost.dealer_discount"
/>
{{-- Manufacturer Discount --}}
<x-inputs.percentage
        label="Manufacturer Discount"
        name="manufacturer_discount"
        wire:model.blur="cost.manufacturer_discount"
/>
{{-- Total Discount --}}
<x-inputs.percentage
        label="Total Discount"
        name="total_discount"
        :value="$cost->invoiceData()['total_discount_percent']"
        readonly
/>

{{-- Metallic Paint Discount --}}
<x-inputs.percentage
    label="Metallic Paint Discount"
    name="metallic_paint_discount"
    :value="$cost->invoiceData()['metallic_discount_percent']"
    readonly
/>

{{-- Leden Flat Discount --}}
<x-inputs.currency label="Leden Flat Discount" name="cost.leden_discount" />

{{-- Manufacturer Delivery Cost --}}
<x-inputs.currency label="Manufacturer Delivery Cost" name="cost.manufacturer_delivery_cost" />

{{-- 1st Reg Fee --}}
<x-inputs.currency label="1st Reg Fee" name="cost.first_reg_fee" />

{{-- RFL --}}
<x-inputs.currency label="RFL" name="cost.rfl_cost" />

{{-- Onward Delivery --}}
<x-inputs.currency label="Onward Delivery" name="cost.onward_delivery" />

{{-- Available Offers --}}
@if ($cost->availableOffers()->isNotEmpty())
    <div class="mb-3 p-3 border rounded bg-light">
        <label class="form-label d-block fw-bold">Available Tactical Offers</label>
        @foreach($cost->availableOffers() as $offer)
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
                   wire:model.live="cost.selectedOffers"
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
<x-inputs.currency label="Invoice Funder For" name="cost.invoice_funder_for" />

<x-inputs.currency label="Grant Value" name="cost.grant" />

{{-- Estimated Invoice Value --}}
<x-inputs.currency-readonly label="Estimated Invoice Value" name="invoice_value" :value="$cost->invoiceData()['invoice_value']" />

{{-- Show Discount Applied --}}
<x-inputs.boolean label="Show Discount Applied" name="cost.show_discount" />

{{-- Show as Offer --}}
<x-inputs.boolean label="Show as Offer" name="cost.show_offer" />

{{-- Hide From Broker --}}
<x-inputs.boolean label="Hide From Broker List" name="cost.hide_from_broker" />

{{-- Hide From Dealer --}}
<x-inputs.boolean label="Hide From Dealer List" name="cost.hide_from_dealer" />
