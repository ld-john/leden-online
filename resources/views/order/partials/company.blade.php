{{-- Dealership --}}
<x-inputs.select name="vehicleForm.dealer_id" label="Dealership" :options="$dealers" placeholder="Select Dealership" required option-label="company_name" />

{{-- Registration Company --}}
<x-inputs.select name="registration_company" label="Registration Company" :options="$registration_companies" placeholder="Select Registration Company" option-label="company_name" />

{{-- Invoice Company --}}
<x-inputs.select name="invoice_company" label="Invoice Company" :options="$invoice_companies" placeholder="Select Invoice Company" option-label="company_name" />

{{-- Broker --}}
<x-inputs.select name="vehicleForm.broker_id" label="Broker" :options="$brokers" required option-label="company_name" placeholder="Select Broker" />

{{-- broker Order Ref --}}
<x-inputs.text name="broker_ref" label="Broker Order Ref" />

{{-- Order Ref --}}
<x-inputs.text name="vehicleForm.ford_order_number" label="Order Ref" required />
