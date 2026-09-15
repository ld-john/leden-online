{{-- Finance Type --}}
<x-inputs.select name="financeType" label="Finance Type" :options="$financeTypeOptions" option-label="option" />

{{-- Maintenance --}}
<x-inputs.select name="maintenance" label="Maintenance" :options="$maintenanceOptions" option-label="option" />

{{-- Terms --}}
<x-inputs.select name="term" label="Terms" :options="$termOptions" option-label="option" />

{{-- Initial Payments --}}
<x-inputs.select name="initialPayments" label="Initial Payments" :options="$initialPaymentsOptions" option-label="option" />

{{-- Terminal Pause --}}
<x-inputs.boolean name="terminal_pause" label="Terminal Pause" />

{{-- Mileage --}}
<x-inputs.select name="mileage" label="Mileage" :options="$mileageOptions" option-label="option" />

{{-- Rental --}}
<x-inputs.currency name="rental_value" label="Rental" suffix="+ VAT" />

{{-- Maintenace Rental --}}
<x-inputs.currency name="maintenance_rental_value" label="Maintenance Rental" suffix="+ VAT" />

{{-- Renewal Date --}}
<x-inputs.date name="renewal_date" label="Renewal Date" />

{{-- Broker --}}
<x-inputs.select name="finance_broker" label="Broker" :options="$brokers" option-label="company_name" :is-overridden="$finance_broker_toggle" override-name="finance_broker_toggle"/>
