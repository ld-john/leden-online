{{-- Make (Required) --}}
<x-inputs.select name="make" label="Make" :options="$manufacturers" required :add-url="route('meta.make.index')" />

{{-- Model (Required) --}}
<x-inputs.select name="model" label="Vehicle Model" :options="$vehicle_models" required/>

{{-- Orbit Number (Unique ID) --}}
<x-inputs.text name="orbit_number" label="Orbit Number" />

{{-- Type (Required) --}}
<x-inputs.select name="type" label="Vehicle Type" :options="$types" option-label="name" option-value="name" required/>

{{-- Reg Number --}}
<x-inputs.text name="registration" label="Registration Number" />

{{-- Meta Attributes with Add Buttons --}}
<x-inputs.select name="derivative" label="Derivative" :options="$derivatives" option-value="name" option-label="name" required :add-url="route('meta.derivative.index')" />
<x-inputs.select name="engine" label="Engine" :options="$engines" option-value="name" option-label="name" required :add-url="route('meta.engine.index')" />
<x-inputs.select name="transmission" label="Transmission" :options="$transmissions" option-value="name" option-label="name" required :add-url="route('meta.transmission.index')" />
<x-inputs.select name="fuel_type" label="Fuel Type" :options="$fuel_types" option-value="name" option-label="name" required :add-url="route('meta.fuel.index')" />
<x-inputs.select name="colour" label="Colour" :options="$colours" option-value="name" option-label="name" required :add-url="route('meta.colour.index')" />
<x-inputs.select name="trim" label="Trim" :options="$trims" option-value="name" option-label="name" required :add-url="route('meta.trim.index')" />


{{-- Chassis --}}
<x-inputs.text name="chassis_prefix" label="Chassis Prefix" />
<x-inputs.text name="chassis" label="Chassis" />

{{-- Vehicle Status --}}
<x-inputs.select name="status" label="Vehicle Status" :options="$this->availableStatuses" required placeholder="Please Select Status" />
{{-- Recall Reason --}}
@if($status?->isRecalled())
    <x-inputs.textarea name="recall_reason" label="Describe the reason for the Recall" />
@endif

{{-- Dates --}}
<x-inputs.date name="due_date" label="Due Date to Dealer" />
<x-inputs.date name="build_date" label="Proposed Build Date" />
@if($status?->isDeliveryEligible() )
    {{-- Delivery Date --}}
    <x-inputs.date name="delivery_date" label="Delivery Date" />
@endif
<x-inputs.date name="order_date" label="Order Date" />
<x-inputs.date name="contract_date" label="Contact Date" />

{{-- Model Year --}}
<div class="form-group row">
    <label class="col-md-2 col-form-label" for="model_year">Model Year</label>
    <div class="col-md-6">
        <input type="text" name="model_year" id="model_year" class="form-control"
               autocomplete="off" wire:model.live="model_year"
               onchange="this.dispatchEvent(new InputEvent('input'))"/>
    </div>
</div>
{{-- Vehicle Registered On --}}
<x-inputs.date name="registered_date" label="Vehicle Registered" />

{{-- Show in Ford Pipeline --}}
<x-inputs.boolean name="ford_pipeline" label="Show in Ford Pipeline" />
