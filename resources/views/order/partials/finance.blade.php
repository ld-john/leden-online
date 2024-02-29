{{-- Finance Type --}}
<div class="form-group row">
    <label for="finance_type" class="col-md-2 col-form-label">Finance Type</label>
    <div class="col-md-6">
        <select wire:model.live="financeType" class="form-select" name="finance_type" id="finance_type">
            <option value="">Choose...</option>
            @foreach($financeTypeOptions as $type)
                <option value="{{$type->id}}">{{$type->option}}</option>
            @endforeach
        </select>
    </div>
</div>
{{-- Maintenance --}}
<div class="form-group row">
    <label for="maintenance" class="col-md-2 col-form-label">Maintenance</label>
    <div class="col-md-6">
        <select wire:model.live="maintenance" class="form-select" name="maintenance" id="maintenance">
            <option value="">Choose...</option>
            @foreach($maintenanceOptions as $type)
                <option value="{{$type->id}}">{{$type->option}}</option>
            @endforeach
        </select>
    </div>
</div>
{{-- Terms --}}
<div class="form-group row">
    <label for="term" class="col-md-2 col-form-label">Terms</label>
    <div class="col-md-6">
        <select wire:model.live="term" class="form-select" name="term" id="term">
            <option value="">Choose...</option>
            @foreach($termOptions as $type)
                <option value="{{$type->id}}">{{$type->option}}</option>
            @endforeach
        </select>
    </div>
</div>
{{-- Initial Payments --}}
<div class="form-group row">
    <label for="initial_payments" class="col-md-2 col-form-label">Initial Payments</label>
    <div class="col-md-6">
        <select wire:model.live="initialPayments" class="form-select" name="initial_payments" id="initial_payments">
            <option value="">Choose...</option>
            @foreach($initialPaymentsOptions as $type)
                <option value="{{$type->id}}">{{$type->option}}</option>
            @endforeach
        </select>
    </div>
</div>

{{-- Terminal Pause --}}
<div class="form-group row">
    <label for="terminal_pause" class="col-md-2 col-form-label">Terminal Pause</label>
    <div class="col-md-6">
        <select wire:model.live="terminalPause" class="form-select" name="terminal_pause" id="terminal_pause">
            <option value="">Choose...</option>
            <option value="yes">Yes</option>
            <option value="no">No</option>
        </select>
    </div>
</div>

{{-- Mileage --}}
<div class="form-group row">
    <label for="mileage" class="col-md-2 col-form-label">Mileage</label>
    <div class="col-md-6">
        <select wire:model.live="mileage" class="form-select" name="mileage" id="mileage">
            <option value="">Choose...</option>
            @foreach($mileageOptions as $type)
                <option value="{{$type->id}}">{{$type->option}}</option>
            @endforeach
        </select>
    </div>
</div>
{{-- Rental --}}
<div class="form-group row">
    <label for="rental_value" class="col-md-2 col-form-label">Rental</label>
    <div class="col-md-6">
        <div class="input-group">
            <div class="input-group-text">
                £
            </div>
            <input wire:model.live="rental_value" type="number" name="rental_value" id="rental_value" step=".01"
                   class="form-control" autocomplete="off" />
            <div class="input-group-text">
                + VAT
            </div>
        </div>
    </div>
</div>

{{-- Maintenace Rental --}}
<div class="form-group row">
    <label for="maintenance_rental_value" class="col-md-2 col-form-label">Maintenance Rental</label>
    <div class="col-md-6">
        <div class="input-group">
            <div class="input-group-text">
                £
            </div>
            <input wire:model.live="maintenance_rental_value" type="number" name="maintenance_rental_value" id="maintenance_rental_value" step=".01"
                   class="form-control" autocomplete="off" />
            <div class="input-group-text">
                + VAT
            </div>
        </div>
    </div>
</div>

{{-- Renewal Date --}}
<div class="form-group row">
    <label for="renewal_date" class="col-md-2 col-form-label">Renewal Date</label>
    <div class="col-md-6">
        <input wire:model.live="renewal_date" type="date" name="renewal_date" id="renewal_date" class="form-control" autocomplete="off" />
    </div>
</div>

{{-- Broker --}}
<div class="form-group row">
    <label class="col-md-2 col-form-label" for="inputGroupFinanceBroker"><i class="fa fa-asterisk fa-fw text-danger" aria-hidden="true"></i> Broker</label>
    <div class="col-md-6">

        <div class="input-group mb-3">
            @error('finance_broker')
            <label class="input-group-text bg-danger text-white" for="inputGroupFinanceBroker"><i class="fa fa-exclamation-triangle"></i></label>
            @enderror

            <div class="input-group-text">
                <span class="form-check-label me-2">Separate Broker?</span>
                <input type="checkbox" class="form-check" aria-label="Checkbox for Invoice Value Override" wire:model.live="finance_broker_toggle">
            </div>
            <select wire:model.live="finance_broker" class="form-select" id="inputGroupFinanceBroker" @if(!$finance_broker_toggle) disabled @endif>
                <option selected>Choose...</option>
                @if ( $brokers )
                    @foreach ($brokers as $broker)
                        <option value="{{ $broker->id }}">{{ $broker->company_name }}</option>
                    @endforeach
                @endif
            </select>
        </div>
    </div>
</div>