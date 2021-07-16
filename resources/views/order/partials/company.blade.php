{{-- Dealership --}}
<div class="form-group row">
    <label for="dealership" class="col-md-2 col-form-label">Dealership</label>
    <div class="col-md-6">
        <select wire:model="dealership" name="dealership" id="dealership" class="form-control">
            <option value="">Select Dealership</option>
            @foreach ($dealers as $dealer)
                <option value="{{ $dealer->id }}">{{ $dealer->company_name }}</option>
            @endforeach
        </select>
    </div>
</div>
{{-- Registration Company --}}
<div class="form-group row">
    <label for="dealership" class="col-md-2 col-form-label">Registration Company</label>
    <div class="col-md-6">
        <select wire:model="registration_company" name="registration_company" id="registration_company" class="form-control">
            <option value="">Select Registration Company</option>

            @if ( $registration_companies )

                @foreach ($registration_companies as $company)
                    <option value="{{ $company->id }}"
                            @if ($company->id == $order_details->registration_company) selected @endif>{{ $company->name }}</option>
                @endforeach

            @endif

        </select>
    </div>
</div>
{{-- Invoice Company --}}
<div class="form-group row">
    <label for="invoice_company" class="col-md-2 col-form-label">Invoice Company</label>
    <div class="col-md-6">
        <select wire:model="invoice_company" name="invoice_company" id="invoice_company" class="form-control">
            <option value="">Select Invoice Company</option>

            @if ( $invoice_companies )

                @foreach ($invoice_companies as $company)
                    <option value="{{ $company->id }}"
                            @if ($company->id == $order_details->invoice_company) selected @endif>{{ $company->name }}</option>
                @endforeach

            @endif

        </select>
    </div>
</div>
{{-- Broker --}}
<div class="form-group row">
    <label class="col-md-2 col-form-label" for="broker"><i class="fa fa-asterisk fa-fw text-danger" aria-hidden="true"></i> Broker
        <button type="button" class="btn-tooltip" data-toggle="tooltip"
                data-placement="right"
                title="If left blank, a notification will be sent to all Brokers when this order is added. If a Broker is selected, then a notification will only be sent to those users associated to that Broker.">
            <i class="fa fa-question-circle"></i>
        </button>
    </label>
    <div class="col-md-6">

        <div class="input-group mb-3">
            @error('broker')
            <div class="input-group-prepend">
                <label class="input-group-text bg-danger text-white" for="inputGroupSelectBroker"><i class="fa fa-exclamation-triangle"></i></label>
            </div>
            @enderror
            <select wire:model="broker" class="custom-select" id="inputGroupSelectBroker">
                <option selected>Choose...</option>

                @if ( brokers )

                    @foreach ($brokers as $broker)
                    <option value="{{ $broker->id }}">{{ $broker->company_name }}</option>
                @endforeach

                @endif
            </select>
        </div>
    </div>
</div>
{{-- broker Order Ref --}}
<div class="form-group row">
    <label class="col-md-2 col-form-label" for="broker_order_ref">Broker Order Ref</label>
    <div class="col-md-6">
        <input wire:model="broker_ref" type="text" name="broker_order_ref" id="broker_order_ref"
               class="form-control" autocomplete="off" placeholder="e.g. Q1780361"/>
    </div>
</div>
{{-- Order Ref --}}
<div class="form-group row">
    <label class="col-md-2 col-form-label" for="order_ref">Order Ref</label>
    <div class="col-md-6">
        <input wire:model="order_ref" type="text" name="order_ref" id="order_ref" class="form-control"
               autocomplete="off" placeholder="e.g. K0047"/>
    </div>
</div>