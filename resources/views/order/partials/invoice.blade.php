{{-- Commision to finance company cost --}}
<div class="form-group row">
    <label for="invoice_finance" class="col-md-2 col-form-label">Commission to Finance Company (£)</label>
    <div class="col-md-6">
        <input wire:model="invoice_finance" type="number" name="invoice_finance" id="invoice_finance" step=".01"
               class="form-control" autocomplete="off" placeholder="e.g. 134.25"/>
    </div>
</div>
{{-- Commision to finance company reference--}}
<div class="form-group row">
    <label for="invoice_finance_number" class="col-md-2 col-form-label">Commission to Finance Company Invoice Number</label>
    <div class="col-md-6">
        <input wire:model="invoice_finance_number" type="text" name="invoice_finance_number" id="invoice_finance_number"
               class="form-control" autocomplete="off" placeholder="e.g. CF1234" />
    </div>
</div>
{{-- Commision to finance company pay date --}}
<div class="form-group row">
    <label class="col-md-2 col-form-label" for="finance_commission_paid">Finance Commission Pay Date</label>
    <div class="col-md-6">
        <input wire:model="finance_commission_paid" type="text" name="finance_commission_paid" id="finance_commission_paid"
               class="form-control" autocomplete="off" placeholder="e.g. 30/03/2019" onchange="this.dispatchEvent(new InputEvent('input'))" />
    </div>
</div>
{{-- Invoice to broker cost --}}
<div class="form-group row">
    <label for="invoice_broker" class="col-md-2 col-form-label">Invoice to Broker (£)</label>
    <div class="col-md-6">
        <input wire:model="invoice_value_to_broker" type="number" name="invoice_broker" id="invoice_broker" step=".01"
               class="form-control" autocomplete="off" placeholder="e.g. 85.71" />
    </div>
</div>
{{-- Invoice to broker reference --}}
<div class="form-group row">
    <label for="invoice_broker_number" class="col-md-2 col-form-label">Invoice to Broker Invoice Number</label>
    <div class="col-md-6">
        <input wire:model="invoice_broker_number" type="text" name="invoice_broker_number" id="invoice_broker_number"
               class="form-control" autocomplete="off" placeholder="e.g. BO568"/>
    </div>
</div>
{{-- Invoice to broker pay date --}}
<div class="form-group row">
    <label class="col-md-2 col-form-label" for="invoice_broker_paid">Broker Invoice Pay Date</label>
    <div class="col-md-6">
        <input wire:model="invoice_broker_paid" type="text" name="invoice_broker_paid" id="invoice_broker_paid"
               class="form-control" autocomplete="off" placeholder="e.g. 30/03/2019" onchange="this.dispatchEvent(new InputEvent('input'))" />
    </div>
</div>
{{-- Invoice from Dealer override price --}}
<div class="form-group row">
    <label for="dealer_invoice_override" class="col-md-2 col-form-label">Override the Dealer from Invoice value (£)</label>
    <div class="col-md-6">
        <input wire:model="dealer_invoice_override" type="number" name="dealer_invoice_override" id="dealer_invoice_override" step=".01"
               class="form-control" autocomplete="off" placeholder="e.g. 134.25"/>
    </div>
</div>

{{-- Invoice from dealer reference --}}
<div class="form-group row">
    <label for="dealer_invoice_number" class="col-md-2 col-form-label">Invoice from Dealer Invoice Number</label>
    <div class="col-md-6">
        <input wire:model="dealer_invoice_number" type="text" name="dealer_invoice_number" id="dealer_invoice_number"
               class="form-control" autocomplete="off" placeholder="e.g. BO568"/>
    </div>
</div>
{{-- Invoice from dealer pay date --}}
<div class="form-group row">
    <label class="col-md-2 col-form-label" for="dealer_pay_date">Invoice from Dealer Pay Date</label>
    <div class="col-md-6">
        <input wire:model="dealer_pay_date" type="text" name="dealer_pay_date" id="dealer_pay_date"
               class="form-control" autocomplete="off" placeholder="e.g. 30/03/2019" onchange="this.dispatchEvent(new InputEvent('input'))"/>
    </div>
</div>
{{-- Commission to broker cost --}}
<div class="form-group row">
    <label for="commission_broker" class="col-md-2 col-form-label">Commission to Broker (£)</label>
    <div class="col-md-6">
        <input wire:model="commission_broker" type="number" name="commission_broker" id="commission_broker" step=".01"
               class="form-control" autocomplete="off" placeholder="e.g. 420.81"/>
    </div>
</div>
{{-- Commission to broker reference --}}
<div class="form-group row">
    <label for="commission_broker_number" class="col-md-2 col-form-label">Commission to Broker Invoice Number</label>
    <div class="col-md-6">
        <input wire:model="commission_broker_number" type="text" name="commission_broker_number" id="commission_broker_number"
               class="form-control" autocomplete="off" placeholder="e.g. CB4097"/>
    </div>
</div>
{{-- Commission to broker pay date --}}
<div class="form-group row">
    <label class="col-md-2 col-form-label" for="commission_broker_paid">Broker Commission Pay Date</label>
    <div class="col-md-6">
        <input wire:model="commission_broker_paid" type="text" name="commission_broker_paid" id="commission_broker_paid"
               class="form-control" autocomplete="off" placeholder="e.g. 30/03/2019" onchange="this.dispatchEvent(new InputEvent('input'))"/>
    </div>
</div>

