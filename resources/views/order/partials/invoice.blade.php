<div class="invoice-box">
    {{-- Commision to finance company cost --}}
    <x-inputs.currency name="invoice_finance" label="Commission to Finance Company" />

    {{-- Commision to finance company reference--}}
    <x-inputs.text name="invoice_finance_number" label="Commission to Finance Company Invoice Number" />

    {{-- Commision to finance company pay date --}}
    <x-inputs.date name="finance_commission_paid" label="Finance Commission Pay Date" />
</div>
<div class="invoice-box">
    {{-- Invoice from Dealer override price --}}
    <x-inputs.currency
            name="dealer_invoice_override"
            :label="$dealer_invoice_override > -1 || !$dealer_invoice_override ? 'Invoice to Dealer Value' : 'Invoice from Dealer Value'"
            override-name="dealer_invoice_override_allowed"
            :is-overridden="$dealer_invoice_override_allowed"
    />
    
    {{-- Invoice from dealer reference --}}
    <x-inputs.text name="dealer_invoice_number" :label="$dealer_invoice_override > -1 || !$dealer_invoice_override ? 'Invoice to Dealer Number' : 'Invoice from Dealer Number'" />

    {{-- Invoice from dealer pay date --}}
    <x-inputs.date name="dealer_pay_date" :label="$dealer_invoice_override > -1 || !$dealer_invoice_override ? 'Invoice to Dealer Pay Date' : 'Invoice from Dealer Pay Date'" />
</div>
<div class="invoice-box">
    <x-inputs.currency name="fleet_procure_invoice" label="Fleet Procure Invoice" />
    <x-inputs.text name="fleet_invoice_number" label="Fleet Procure Invoice Number" />
    <x-inputs.date name="fleet_procure_paid" label="Fleet Procure Invoice Pay Date" />
</div>
<div class="invoice-box">
    <x-inputs.currency name="invoice_value_to_broker" label="Invoice to Broker" />
    <x-inputs.text name="invoice_broker_number" label="Invoice to Broker Invoice Number" />
    <x-inputs.date name="invoice_broker_paid" label="Broker Invoice Pay Date" />
</div>
<div class="invoice-box">
    <x-inputs.currency name="commission_broker" label="Commission to Broker" />
    <x-inputs.text name="commission_broker_number" label="Commission to Broker Invoice Number" />
    <x-inputs.date name="commission_broker_paid" label="Commission Invoice Pay Date" />
</div>
<div class="invoice-box">
    <x-inputs.currency name="finance_bonus_invoice" label="Finance Company Bonus Invoice" />
    <x-inputs.text name="finance_company_bonus_invoice_number" label="Finance Company Bonus Invoice Number" />
    <x-inputs.date name="finance_company_bonus_pay_date" label="Finance Company Bonus Pay Date" />
</div>
<div class="invoice-box">
    <x-inputs.currency name="ford_bonus_invoice" label="Ford Bonus Invoice" />
    <x-inputs.date name="ford_bonus_pay_date" label="Ford Bonus Pay Date" />
</div>
