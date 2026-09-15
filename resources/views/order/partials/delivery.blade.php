{{-- Customer Desired Delivery Month --}}
<x-inputs.select label="Desired Delivery Month" name="delivery_month" placeholder="---" :options="$delivery_months" />

{{-- Customer Phone Number --}}
<x-inputs.text label="Customer Phone Number" name="customer.phone_number" />

{{-- Address 1 --}}
<x-inputs.text label="Address Line 1" name="customer.address_1" required />

{{-- Address 2 --}}
<x-inputs.text label="Address Line 2" name="customer.address_2" />

{{-- Town --}}
<x-inputs.text label="Town" name="customer.town" />

{{-- City --}}
<x-inputs.text label="City" name="customer.city" />

{{-- County --}}
<x-inputs.text label="County" name="customer.county" />

{{-- Postcode --}}
<x-inputs.text label="Postcode" name="customer.postcode" />
