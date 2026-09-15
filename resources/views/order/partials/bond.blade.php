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
                        disabled
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
                   disabled
            >
            <label class="form-check-label text-muted" for="offer-none">
                No offer applied
            </label>
        </div>
    </div>
@endif

@if($status?->isDeliveryEligible() )
    {{-- Delivery Date --}}
    <x-inputs.date name="delivery_date" label="Delivery Date" disabled />
@endif

<x-inputs.currency name="bond.bond_value" label="Bond Value" />
<x-inputs.date name="bond.payment_date" label="Payment Date" />
<x-inputs.currency name="bond.refund_value" label="Refund Value" />
<x-inputs.date name="bond.refund_date" label="Refund Date" />
