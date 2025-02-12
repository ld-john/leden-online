{{-- Make (Required) --}}
<div class="form-group row">
    <label class="col-md-2 col-form-label" for="vehicle_make"><i class="fa fa-asterisk fa-fw text-danger" aria-hidden="true"></i>Make</label>
    <div class="col-md-6">
        <div class="input-group mb-3">
            @error('make')
            <label class="input-group-text bg-danger text-white" for="inputGroupSelectMake"><i class="fa fa-exclamation-triangle"></i></label>
            @enderror
            <select wire:model.live="make" class="form-select" id="inputGroupSelectMake">
                <option selected>Choose...</option>
                @foreach ( $manufacturers as $manufacturer )
                    <option value="{{ $manufacturer->id }}">{{ $manufacturer->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-2">
        <a class="btn btn-secondary remove-selected"
           data-toggle="tooltip"
           title="Add New Model or Make"
           href="{{ route('meta.make.index') }}"
        >
            <i class="fa-solid fa-plus"></i>
        </a>
    </div>
</div>
{{-- Model (Required) --}}
<div class="form-group row">
    <label class="col-md-2 col-form-label" for="vehicle_model"><i class="fa fa-asterisk fa-fw text-danger" aria-hidden="true"></i>Vehicle Model</label>
    <div class="col-md-6">
        <div class="input-group mb-3">
            @error('model')
            <label class="input-group-text bg-danger text-white" for="inputGroupSelectModel"><i class="fa fa-exclamation-triangle"></i></label>
            @enderror
            <select class="form-control value-change" @if(count($vehicle_models) === 0) disabled @endif field-parent="vehicle_model" wire:model.live="model" id="inputGroupSelectModel">
                <option value="" selected>Choose...</option>
                @foreach($vehicle_models as $vehicle_model)
                    <option value="{{$vehicle_model->id}}">{{ $vehicle_model->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>
{{-- Orbit Number (Unique ID) --}}
<div class="form-group row">
    <label for="orbit_number" class="col-md-2 col-form-label">Orbit Number</label>
    <div class="col-md-6">
        @error('orbit_number')
        <div class="input-group-text">
            <label for="inputGroupOrbitNumberText" class="input-group-text bg-danger text-white"><i class="fa fa-exclamation-triangle"></i></label>
        </div>
        @enderror
        <input wire:model.live="orbit_number" type="text" name="orbit_number" id="orbit_number" class="form-control"
               autocomplete="off" />
    </div>
</div>
{{-- Type (Required) --}}
<div class="form-group row">
    <label class="col-md-2 col-form-label" for="vehicle_type"><i class="fa fa-asterisk fa-fw text-danger" aria-hidden="true"></i>Vehicle Type</label>
    <div class="col-md-6">
        <div class="input-group mb-3">
            @error('type')
            <label class="input-group-text bg-danger text-white" for="inputGroupSelectType"><i class="fa fa-exclamation-triangle"></i></label>
            @enderror
            <select wire:model.live="type" @if(count($types) === 0) disabled @endif class="form-select" id="inputGroupSelectType">
                <option selected>Choose...</option>
                @foreach ($types as $type)
                    <option value="{{$type->name}}">{{$type->name}}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>
{{-- Reg Number --}}
<div class="form-group row">
    <label class="col-md-2 col-form-label" for="vehicle_reg">Registration Number</label>
    <div class="col-md-6 mb-3">
        <input wire:model.live="registration" type="text" name="vehicle_reg" id="vehicle_reg" class="form-control"/>
    </div>
</div>
{{-- Derivative (Required) --}}
<div class="form-group row">
    <label class="col-md-2 col-form-label" for="vehicle_derivative"><i class="fa fa-asterisk fa-fw text-danger" aria-hidden="true"></i>Derivative</label>
        <div class="col-md-6">
            <div class="input-group mb-3">
                @error('derivative')
                <label class="input-group-text bg-danger text-white" for="inputGroupSelectDerivatives"><i class="fa fa-exclamation-triangle"></i></label>
                @enderror

                <select wire:model.live="derivative" @if(count($derivatives) === 0) disabled @endif class="form-select" id="inputGroupSelectDerivatives">
                    <option selected>Choose...</option>
                    @foreach ($derivatives as $vehicle_derivative)
                        <option value="{{ $vehicle_derivative->name }}">{{ $vehicle_derivative->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    <div class="col-md-2">
        <a class="btn btn-secondary remove-selected"
           data-toggle="tooltip"
           title="Add or Edit Derivatives"
           href="{{ route('meta.derivative.index') }}"
        >
            <i class="fa-solid fa-plus"></i>
        </a>
    </div>
</div>
{{-- Engine (Required) --}}
<div class="form-group row">
    <label class="col-md-2 col-form-label" for="vehicle_engine"><i class="fa fa-asterisk fa-fw text-danger" aria-hidden="true"></i>Engine</label>
        <div class="col-md-6">
            <div class="input-group mb-3">
                @error('engine')
                <label class="input-group-text bg-danger text-white" for="inputGroupSelectEngine"><i class="fa fa-exclamation-triangle"></i></label>
                @enderror
                <select wire:model.live="engine" @if(count($engines) === 0) disabled @endif class="form-select" id="inputGroupSelectEngine">
                    <option selected>Choose...</option>
                    @foreach ($engines as $vehicle_engine)
                        <option value="{{ $vehicle_engine->name }}">{{ $vehicle_engine->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
       <div class="col-md-2">
           <a class="btn btn-secondary remove-selected"
              data-toggle="tooltip"
              title="Add or Edit Engines"
              href="{{ route('meta.engine.index') }}"
           >
               <i class="fa-solid fa-plus"></i>
           </a>
    </div>
</div>
{{-- Transmission (Required) --}}
<div class="form-group row">
    <label class="col-md-2 col-form-label" for="vehicle_trans"><i class="fa fa-asterisk fa-fw text-danger" aria-hidden="true"></i>Transmission</label>
        <div class="col-md-6">
            <div class="input-group mb-3">
                @error('transmission')
                <label class="input-group-text bg-danger text-white" for="inputGroupSelectTransmission"><i class="fa fa-exclamation-triangle"></i></label>
                @enderror
                <select wire:model.live="transmission" @if(count($transmissions) === 0) disabled @endif class="form-select" id="inputGroupSelectTransmission">
                    <option selected>Choose...</option>
                    @foreach ($transmissions as $vehicle_trans)
                        <option value="{{ $vehicle_trans->name }}">{{ $vehicle_trans->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    <div class="col-md-2">
        <a class="btn btn-secondary remove-selected"
           data-toggle="tooltip"
           title="Add or Edit Transmission"
           href="{{ route('meta.transmission.index') }}"
        >
            <i class="fa-solid fa-plus"></i>
        </a>
    </div>
</div>
{{-- Fuel Type --}}
<div class="form-group row">
    <label class="col-md-2 col-form-label" for="fuel_type"><i class="fa fa-asterisk fa-fw text-danger" aria-hidden="true"></i>Fuel Type</label>
        <div class="col-md-6">
            <div class="input-group mb-3">
                @error('fuel_type')
                <label class="input-group-text bg-danger text-white" for="inputGroupSelectFuel"><i class="fa fa-exclamation-triangle"></i></label>
                @enderror
                <select wire:model.live="fuel_type" @if(count($fuel_types) === 0) disabled @endif class="form-select" id="inputGroupSelectFuel">
                    <option selected>Choose...</option>
                    @foreach ($fuel_types as $vehicle_fuel_type)
                        <option value="{{ $vehicle_fuel_type->name }}">{{ $vehicle_fuel_type->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    <div class="col-md-2">
        <a class="btn btn-secondary remove-selected"
           data-toggle="tooltip"
           title="Add or Edit Fuel Types"
           href="{{ route('meta.fuel.index') }}"
        >
            <i class="fa-solid fa-plus"></i>
        </a>
    </div>
</div>
{{-- Colour --}}
<div class="form-group row">
    <label class="col-md-2 col-form-label" for="vehicle_colour"><i class="fa fa-asterisk fa-fw text-danger" aria-hidden="true"></i>Colour</label>
        <div class="col-md-6">
            <div class="input-group mb-3">
                @error('colour')
                <label class="input-group-text bg-danger text-white" for="inputGroupSelectColour"><i class="fa fa-exclamation-triangle"></i></label>
                @enderror
                <select wire:model.live="colour" @if(count($colours) === 0) disabled @endif class="form-select" id="inputGroupSelectColour">
                    <option selected >Choose...</option>
                    @foreach ($colours as $vehicle_colour)
                        <option value="{{ $vehicle_colour->name }}">{{ $vehicle_colour->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    <div class="col-md-2">
        <a class="btn btn-secondary remove-selected"
           data-toggle="tooltip"
           title="Add or Edit Colours"
           href="{{ route('meta.colour.index') }}"
        >
            <i class="fa-solid fa-plus"></i>
        </a>
    </div>
</div>
{{-- Trim --}}
<div class="form-group row">
    <label class="col-md-2 col-form-label" for="vehicle_trim"><i class="fa fa-asterisk fa-fw text-danger" aria-hidden="true"></i>Trim</label>
        <div class="col-md-6">
            <div class="input-group mb-3">
                @error('trim')
                <label class="input-group-text bg-danger text-white" for="inputGroupSelectTrim"><i class="fa fa-exclamation-triangle"></i></label>
                @enderror
                <select wire:model.live="trim" @if(count($trims) === 0) disabled @endif class="form-select" id="inputGroupSelectTrim">
                    <option selected>Choose...</option>
                    @foreach ($trims as $vehicle_trim)
                        <option value="{{ $vehicle_trim->name }}">{{ $vehicle_trim->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    <div class="col-md-2">
        <a class="btn btn-secondary remove-selected"
           data-toggle="tooltip"
           title="Add or Edit Trim"
           href="{{ route('meta.trim.index') }}"
        >
            <i class="fa-solid fa-plus"></i>
        </a>
    </div>
</div>
{{-- Chassis Prefix --}}
<div class="form-group row">
    <label class="col-md-2 col-form-label" for="chassis_prefix">Chassis Prefix</label>
    <div class="col-md-6">
        <input wire:model.live="chassis_prefix" type="text" name="chassis_prefix" id="chassis_prefix" class="form-control"
               autocomplete="off"/>
    </div>
</div>
{{-- Chassis--}}
<div class="form-group row">
    <label class="col-md-2 col-form-label" for="chassis">Chassis</label>
    <div class="col-md-6">
        <input wire:model.live="chassis" type="text" name="chassis" id="chassis" class="form-control"
               autocomplete="off" />
    </div>
</div>
{{-- Vehicle Status --}}
<div class="form-group row">
    <label class="col-md-2 col-form-label" for="vehicle_status"><i class="fa fa-asterisk fa-fw text-danger" aria-hidden="true"></i> Vehicle Status</label>
    <div class="col-md-6">
        <div class="input-group">
            @error('status')
            <label class="input-group-text bg-danger text-white" for="inputGroupSelectStatus"><i class="fa fa-exclamation-triangle"></i></label>
            @enderror
            <select wire:model.live="status" class="form-select" name="vehicle_status" id="inputGroupSelectStatus">
                <option value="">Please Select Status</option>
                <option value="4">Factory Order</option>
                @if ($registered_date)
                    <option value="15">In Stock (Registered)</option>
                @endif
                <option value="1">In Stock</option>
                <option value="3">Ready for Delivery</option>
                <option value="5">Awaiting Delivery Confirmation</option>
                <option value="6">Delivery Booked</option>
                @if ($registered_date && $registered_date < $now)
                    <option value="7">Completed Orders</option>
                @endif
                <option value="10">Europe VHC</option>
                <option value="11">UK VHC</option>
                <option value="12">At Converter</option>
                <option value="13">Awaiting Ship</option>
                <option value="14">Recall</option>
                <option value="16">Damaged/Recalled</option>
                <option value="17">In Stock (Awaiting Dealer Options)</option>
                <option value="18">Dealer Transfer</option>
                <option value="19">Order in Query</option>
            </select>
        </div>
    </div>
</div>
{{-- Due Date --}}
<div class="form-group row">
    <label class="col-md-2 col-form-label" for="due_date">Due Date to Dealer</label>
    <div class="col-md-6">
        <input wire:model.live="due_date" type="date" name="due_date" id="due_date" class="form-control"
               autocomplete="off"
               onchange="this.dispatchEvent(new InputEvent('input'))"
        />
    </div>
</div>
{{-- Build Date --}}
<div class="form-group row">
    <label class="col-md-2 col-form-label" for="build_date">Proposed Build Date</label>
    <div class="col-md-6">
        <input wire:model.live="build_date" type="date" name="build_date" id="build_date" class="form-control" autocomplete="off" />
    </div>
</div>
@if($status === 1 || $status === 3 || $status === 5 || $status === 6 || $status === 15 )
    {{-- Delivery Date --}}
    <div class="form-group row">
        <label class="col-md-2 col-form-label" for="delivery_date">Delivery Date</label>
        <div class="col-md-6">
            <input type="date" name="delivery_date" class="form-control"
                   autocomplete="off" wire:model.live="delivery_date" />
        </div>
    </div>
@endif

<div class="form-group row">
    <label for="order_date" class="col-md-2 col-form-label">Order Date</label>
    <div class="col-md-6">
        <input wire:model.live="order_date" type="date" name="order_date" id="order_date" class="form-control"
               autocomplete="off" />
    </div>
</div>

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
<div class="form-group row">
    <label class="col-md-2 col-form-label" for="registered_date">Vehicle
        Registered</label>
    <div class="col-md-6">
        <input type="date" name="registered_date" class="form-control"
               autocomplete="off" wire:model.live="registered_date" />
    </div>
</div>
{{-- Show in Ford Pipeline --}}
<div class="form-group row">
    <label for="show_in_ford_pipeline" class="col-md-2 col-form-label">Show in Ford
        Pipeline</label>
    <div class="col-md-6">
        <select wire:model.live="ford_pipeline" name="show_in_ford_pipeline" id="show_in_ford_pipeline"
                class="form-control">
            <option value="0">No
            </option>
            <option value="1">Yes
            </option>
        </select>
    </div>
</div>
