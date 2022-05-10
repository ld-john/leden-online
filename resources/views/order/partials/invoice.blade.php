<div class="invoice-box">
    {{-- Commision to finance company cost --}}
    <div class="form-group row">
        <label for="invoice_finance" class="col-md-3 col-form-label">Commission to Finance Company (£)</label>
        <div class="col-md-9">
            <input wire:model="invoice_finance" type="number" name="invoice_finance" id="invoice_finance" step=".01"
                   class="form-control" autocomplete="off" />
        </div>
    </div>
    {{-- Commision to finance company reference--}}
    <div class="form-group row">
        <label for="invoice_finance_number" class="col-md-3 col-form-label">Commission to Finance Company Invoice Number</label>
        <div class="col-md-9">
            <input wire:model="invoice_finance_number" type="text" name="invoice_finance_number" id="invoice_finance_number"
                   class="form-control" autocomplete="off" />
        </div>
    </div>
    {{-- Commision to finance company pay date --}}
    <div class="row">
        <label class="col-md-3 col-form-label" for="finance_commission_paid">Finance Commission Pay Date</label>
        <div class="col-md-9">
            <input wire:model="finance_commission_paid" type="date" name="finance_commission_paid"
                   class="form-control" autocomplete="off" />
        </div>
    </div>
</div>
<div class="invoice-box">
    {{-- Invoice from Dealer override price --}}
    <div class="form-group row">
        <label for="dealer_invoice_override" class="col-md-3 col-form-label">
            @if($dealer_invoice_override > -1 || !$dealer_invoice_override )
                Invoice to Dealer Value (£)
            @else
                Invoice from Dealer Value (£)
            @endif
        </label>
        <div class="col-md-9">
            <input wire:model="dealer_invoice_override" type="number" name="dealer_invoice_override" id="dealer_invoice_override" step=".01"
                   class="form-control" autocomplete="off" />
        </div>
    </div>
    {{-- Invoice from dealer reference --}}
    <div class="form-group row">
        <label for="dealer_invoice_number" class="col-md-3 col-form-label">
            @if($dealer_invoice_override > -1 || !$dealer_invoice_override )
                Invoice to Dealer Invoice Number
            @else
                Invoice from Dealer Invoice Number
            @endif
        </label>
        <div class="col-md-9">
            <input wire:model="dealer_invoice_number" type="text" name="dealer_invoice_number" id="dealer_invoice_number"
                   class="form-control" autocomplete="off" />
        </div>
    </div>
    {{-- Invoice from dealer pay date --}}
    <div class="form-group row">
        <label class="col-md-3 col-form-label" for="dealer_pay_date">
            @if($dealer_invoice_override > -1 || !$dealer_invoice_override )
                Invoice to Dealer Pay Date
            @else
                Invoice from Dealer Pay Date
            @endif
        </label>
        <div class="col-md-9">
            <input wire:model="dealer_pay_date" type="date" name="dealer_pay_date" id="dealer_pay_date"
                   class="form-control" autocomplete="off"/>
        </div>
    </div>
</div>
<div class="invoice-box">
    {{-- Fleet Procure Invoice --}}
    <div class="form-group row">
        <label for="fleet_procure_invoice" class="col-md-3 col-form-label">Fleet Procure Invoice (£)</label>
        <div class="col-md-9">
            <input wire:model="fleet_procure_invoice" type="number" name="fleet_procure_invoice" id="fleet_procure_invoice" step=".01"
                   class="form-control" autocomplete="off" />
        </div>
    </div>
    {{-- Invoice from Fleet Procure Reference --}}
    <div class="form-group row">
        <label for="fleet_invoice_number" class="col-md-3 col-form-label">Fleet Procure Invoice Number</label>
        <div class="col-md-9">
            <input wire:model="fleet_invoice_number" type="text" name="fleet_invoice_number" id="fleet_invoice_number"
                   class="form-control" autocomplete="off" />
        </div>
    </div>
    {{-- Invoice Date from Fleet Procure --}}
    <div class="row">
        <label class="col-md-3 col-form-label" for="fleet_procure_paid">Fleet Procure Invoice Pay Date</label>
        <div class="col-md-9">
            <input wire:model="fleet_procure_paid" type="date" name="fleet_procure_paid"
                   class="form-control" autocomplete="off" />
        </div>
    </div>
</div>
<div class="invoice-box">
    {{-- Invoice to broker cost --}}
    <div class="form-group row">
        <label for="invoice_broker" class="col-md-3 col-form-label">Invoice to Broker (£)</label>
        <div class="col-md-9">
            <input wire:model="invoice_value_to_broker" type="number" name="invoice_broker" id="invoice_broker" step=".01"
                   class="form-control" autocomplete="off" />
        </div>
    </div>
    {{-- Invoice to broker reference --}}
    <div class="form-group row">
        <label for="invoice_broker_number" class="col-md-3 col-form-label">Invoice to Broker Invoice Number</label>
        <div class="col-md-9">
            <input wire:model="invoice_broker_number" type="text" name="invoice_broker_number" id="invoice_broker_number"
                   class="form-control" autocomplete="off" />
        </div>
    </div>
    {{-- Invoice to broker pay date --}}
    <div class="form-group row">
        <label class="col-md-3 col-form-label" for="invoice_broker_paid">Broker Invoice Pay Date</label>
        <div class="col-md-9">
            <input wire:model="invoice_broker_paid" type="date" name="invoice_broker_paid" class="form-control" autocomplete="off"  />
        </div>
    </div>
</div>
<div class="invoice-box">
    {{-- Commission to broker cost --}}
    <div class="form-group row">
        <label for="commission_broker" class="col-md-3 col-form-label">Commission to Broker (£)</label>
        <div class="col-md-9">
            <input wire:model="commission_broker" type="number" name="commission_broker" id="commission_broker" step=".01"
                   class="form-control" autocomplete="off" />
        </div>
    </div>
    {{-- Commission to broker reference --}}
    <div class="form-group row">
        <label for="commission_broker_number" class="col-md-3 col-form-label">Commission to Broker Invoice Number</label>
        <div class="col-md-9">
            <input wire:model="commission_broker_number" type="text" name="commission_broker_number" id="commission_broker_number"
                   class="form-control" autocomplete="off" />
        </div>
    </div>
    {{-- Commission to broker pay date --}}
    <div class="form-group row">
        <label class="col-md-3 col-form-label" for="commission_broker_paid">Broker Commission Pay Date</label>
        <div class="col-md-9">
            <input wire:model="commission_broker_paid" type="date" name="commission_broker_paid"
                   class="form-control" autocomplete="off" />
        </div>
    </div>
</div>
<div class="invoice-box">
    {{-- Finance Company Bonus Invoice --}}
    <div class="form-group row">
        <label for="finance_bonus_invoice" class="col-md-3 col-form-label">Finance Company Bonus Invoice (£)</label>
        <div class="col-md-9">
            <input wire:model="finance_bonus_invoice" type="number" name="finance_bonus_invoice" id="finance_bonus_invoice" step=".01"
                   class="form-control" autocomplete="off" />
        </div>
    </div>
    {{-- Finance Company Bonus Invoice Number --}}
    <div class="form-group row">
        <label for="finance_company_bonus_invoice_number" class="col-md-3 col-form-label">Finance Company Bonus Invoice Number</label>
        <div class="col-md-9">
            <input wire:model="finance_company_bonus_invoice_number" type="text" name="finance_company_bonus_invoice_number" id="finance_company_bonus_invoice_number"
                   class="form-control" autocomplete="off" />
        </div>
    </div>
    {{-- Finance Company Bonus pay date --}}
    <div class="form-group row">
        <label class="col-md-3 col-form-label" for="finance_company_bonus_pay_date">Finance Company Bonus Pay Date</label>
        <div class="col-md-9">
            <input wire:model="finance_company_bonus_pay_date" type="date" name="finance_company_bonus_pay_date"
                   class="form-control" autocomplete="off" />
        </div>
    </div>
</div>
<div class="invoice-box">
    {{-- Ford Bonus Invoice --}}
    <div class="form-group row">
        <label for="ford_bonus_invoice" class="col-md-3 col-form-label">Ford Bonus Invoice (£)</label>
        <div class="col-md-9">
            <input wire:model="ford_bonus_invoice" type="number" name="ford_bonus_invoice" id="ford_bonus_invoice" step=".01"
                   class="form-control" autocomplete="off" />
        </div>
    </div>
    {{-- Ford Bonus pay date --}}
    <div class="form-group row">
        <label class="col-md-3 col-form-label" for="ford_bonus_pay_date">Ford Bonus Pay Date</label>
        <div class="col-md-9">
            <input wire:model="ford_bonus_pay_date" type="date" name="ford_bonus_pay_date"
                   class="form-control" autocomplete="off" />
        </div>
    </div>
</div>

