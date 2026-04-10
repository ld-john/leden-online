{{-- Customer Desired Delivery Month --}}
<x-inputs.select label="Desired Delivery Month" name="delivery_month" placeholder="---" :options="$delivery_months" />

{{-- Customer Phone Number --}}
<x-inputs.text label="Customer Phone Number" name="customer_phone" />

{{-- Address 1 --}}
<x-inputs.text label="Address Line 1" name="delivery_address_1" />

{{-- Address 2 --}}
<x-inputs.text label="Address Line 2" name="delivery_address_2" />

{{-- Town --}}
<x-inputs.text label="Town" name="delivery_town" />

{{-- City --}}
<x-inputs.text label="City" name="delivery_city" />

{{-- County --}}
<x-inputs.text label="County" name="delivery_county" />

{{-- Postcode --}}
<x-inputs.text label="Postcode" name="delivery_postcode" />
