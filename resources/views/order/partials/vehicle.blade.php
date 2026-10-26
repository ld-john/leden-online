{{-- Make (Required) --}}
<x-inputs.select name="vehicleForm.make" label="Make" :options="$manufacturers" required :add-url="route('meta.make.index')" />

{{-- Model (Required) --}}
<x-inputs.select name="vehicleForm.model" label="Vehicle Model" :options="$vehicle_models" required/>

{{-- Orbit Number (Unique ID) --}}
<x-inputs.text name="vehicleForm.orbit_number" label="Orbit Number" />

{{-- Type (Required) --}}
<x-inputs.select name="vehicleForm.type" label="Vehicle Type" :options="$types" option-label="name" option-value="name" required/>

{{-- Reg Number --}}
<x-inputs.text name="vehicleForm.reg" label="Registration Number" />

{{-- Meta Attributes with Add Buttons --}}
<x-inputs.select name="vehicleForm.derivative" label="Derivative" :options="$derivatives" option-value="name" option-label="name" required :add-url="route('meta.derivative.index')" />
<x-inputs.select name="vehicleForm.engine" label="Engine" :options="$engines" option-value="name" option-label="name" required :add-url="route('meta.engine.index')" />
<x-inputs.select name="vehicleForm.transmission" label="Transmission" :options="$transmissions" option-value="name" option-label="name" required :add-url="route('meta.transmission.index')" />
<x-inputs.select name="vehicleForm.fuel_type" label="Fuel Type" :options="$fuel_types" option-value="name" option-label="name" required :add-url="route('meta.fuel.index')" />
<x-inputs.select name="vehicleForm.colour" label="Colour" :options="$colours" option-value="name" option-label="name" required :add-url="route('meta.colour.index')" />
<x-inputs.select name="vehicleForm.trim" label="Trim" :options="$trims" option-value="name" option-label="name" required :add-url="route('meta.trim.index')" />


{{-- Chassis --}}
<x-inputs.text name="vehicleForm.chassis_prefix" label="Chassis Prefix" />
<x-inputs.text name="vehicleForm.chassis" label="Chassis" />

{{-- Vehicle Status --}}
<x-inputs.select name="vehicleForm.vehicle_status" label="Vehicle Status" :options="$this->availableStatuses" required placeholder="Please Select Status" />
{{-- Recall Reason --}}
@if($vehicleForm->vehicle_status?->isRecalled())
    <x-inputs.textarea name="vehicleForm.recall_reason" label="Describe the reason for the Recall" />
@endif

{{-- Dates --}}
<x-inputs.date name="vehicleForm.due_date" label="Due Date to Dealer" />
<x-inputs.date name="vehicleForm.build_date" label="Proposed Build Date" />
@if($vehicleForm->vehicle_status?->isDeliveryEligible() )
    {{-- Delivery Date --}}
    <x-inputs.date name="delivery_date" label="Delivery Date" />
@endif
<x-inputs.date name="order_date" label="Order Date" />
<x-inputs.date name="vehicleForm.contract_date" label="Contact Date" />

{{-- Model Year --}}
<div class="form-group row">
    <label class="col-md-2 col-form-label" for="model_year">Model Year</label>
    <div class="col-md-6">
        <input type="text" name="model_year" id="model_year" class="form-control"
               autocomplete="off" wire:model.live="vehicleForm.model_year"
               onchange="this.dispatchEvent(new InputEvent('input'))"/>
    </div>
</div>
{{-- Vehicle Registered On --}}
<x-inputs.date name="vehicleForm.vehicle_registered_on" label="Vehicle Registered" />

{{-- Show in Ford Pipeline --}}
<x-inputs.boolean name="ford_pipeline" label="Show in Ford Pipeline" />
