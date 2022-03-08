{{-- Make (Required) --}}
<div class="form-group row">

    <label class="col-md-2 col-form-label" for="vehicle_make"><i class="fa fa-asterisk fa-fw text-danger" aria-hidden="true"></i>Make</label>

    <div class="col-md-6">

        @if ( $makeInput )


            <div class="input-group mb-3">
                @error('make')
                <div class="input-group-prepend">
                    <label class="input-group-text bg-danger text-white" for="inputGroupSelectMake"><i class="fa fa-exclamation-triangle"></i></label>
                </div>
                @enderror
                <select wire:model="make" class="custom-select" id="inputGroupSelectMake">
                    <option selected>Choose...</option>
                    @foreach ( $manufacturers as $manufacturer )
                        <option value="{{ $manufacturer->id }}">{{ $manufacturer->name }}</option>
                    @endforeach
                </select>
            </div>

        @else

            <input type="text"
                   name="vehicle_make"
                   id="vehicle_make"
                   class="form-control mb-3"
                   autocomplete="off"
                   placeholder="e.g. Ford"
                   @unless ( is_null( $make ) )
                   value="{{$manufacturers[$make]['name']}}"
                   @endunless
                   wire:model.lazy="newmake"
            />

        @endIf

    </div>

    <div class="col-md-2">
        <button class="btn btn-secondary switch-inputs"
                type="button"
                id="make-switch"
                wire:click.prevent="$set('makeInput' , {{!$makeInput}})"
        >
            @if ( $makeInput )
                <i class="fa fa-terminal"></i>
            @else
                <i class="fa fa-list-alt"></i>
            @endif
        </button>
    </div>

</div>
{{-- Model (Required) --}}
<div class="form-group row">
    <label class="col-md-2 col-form-label" for="vehicle_model"><i class="fa fa-asterisk fa-fw text-danger" aria-hidden="true"></i>Vehicle Model</label>
    <div class="col-md-6">

        @if ( $modelInput )

            <div class="input-group mb-3">


                @error('model')
                <div class="input-group-prepend">
                    <label class="input-group-text bg-danger text-white" for="inputGroupSelectModel"><i class="fa fa-exclamation-triangle"></i></label>
                </div>
                @enderror
                <select class="form-control value-change" @unless ( !is_null( $make ) ) disabled @endunless field-parent="vehicle_model" wire:model="model" id="inputGroupSelectModel">
                    @unless ( is_null( $make ) )
                        <option value="" selected>Choose...</option>
                        @foreach(json_decode($manufacturers[$make]['models']) as $model)
                            <option value="{{$model}}">{{$model}}</option>
                        @endforeach
                    @endunless
                </select>
            </div>
        @else
            <div class="input-group mb-3">
                @error('model')
                <div class="input-group-prepend">
                    <label class="input-group-text bg-danger text-white" for="inputGroupModelText"><i class="fa fa-exclamation-triangle"></i></label>
                </div>
                @enderror
                <input wire:model.lazy="model"
                       type="text"
                       name="vehicle_model"
                       id="inputGroupModelText"
                       class="form-control"
                       placeholder="e.g. Fiesta"
                />
            </div>

        @endif

    </div>

    <div class="col-md-2">
        <button class="btn btn-secondary remove-selected"
                type="button"
                id="model-switch"
                wire:click.prevent="$set('modelInput' , {{!$modelInput}})"
        >
            @if ( $modelInput )
                <i class="fa fa-terminal"></i>
            @else
                <i class="fa fa-list-alt"></i>
            @endif
        </button>
    </div>
</div>
{{-- Orbit Number (Unique ID) --}}
<div class="form-group row">
    <label for="orbit_number" class="col-md-2 col-form-label">Orbit Number</label>
    <div class="col-md-6">
        @error('orbit_number')
        <div class="input-group-prepend">
            <label for="inputGroupOrbitNumberText" class="input-group-text bg-danger text-white"><i class="fa fa-exclamation-triangle"></i></label>
        </div>
        @enderror
        <input wire:model="orbit_number" type="text" name="orbit_number" id="orbit_number" class="form-control"
               autocomplete="off" placeholder="e.g. 66653275"/>
    </div>
</div>
{{-- Type (Required) --}}
<div class="form-group row">
    <label class="col-md-2 col-form-label" for="vehicle_type"><i class="fa fa-asterisk fa-fw text-danger" aria-hidden="true"></i>Vehicle Type</label>
    <div class="col-md-6">
        <div class="input-group mb-3">
            @error('type')
            <div class="input-group-prepend">
                <label class="input-group-text bg-danger text-white" for="inputGroupSelectType"><i class="fa fa-exclamation-triangle"></i></label>
            </div>
            @enderror
            <select wire:model="type" class="custom-select" id="inputGroupSelectType">
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
        <input wire:model="registration" type="text" name="vehicle_reg" id="vehicle_reg" class="form-control" placeholder="e.g. WM63 NKZ"/>
    </div>
</div>
{{-- Derivative (Required) --}}
<div class="form-group row">
    <label class="col-md-2 col-form-label" for="vehicle_derivative"><i class="fa fa-asterisk fa-fw text-danger" aria-hidden="true"></i>Derivative</label>
    @if ( $derivativeInput )
        <div class="col-md-6">
            <div class="input-group mb-3">
                @error('derivative')
                <div class="input-group-prepend">
                    <label class="input-group-text bg-danger text-white" for="inputGroupSelectDerivatives"><i class="fa fa-exclamation-triangle"></i></label>
                </div>
                @enderror

                <select wire:model="derivative" class="custom-select" id="inputGroupSelectDerivatives">
                    <option selected>Choose...</option>
                    @foreach ($derivatives as $vehicle_derivative)
                        <option value="{{ $vehicle_derivative->name }}">{{ $vehicle_derivative->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    @else
        <div class="col-md-6 mb-3">
            <input wire:model="derivative" type="text" name="vehicle_derivative" id="vehicle_derivative" class="form-control" autocomplete="off" placeholder="e.g. ST-Line X PHEV"/>
        </div>
    @endif
    <div class="col-md-2">
        <button class="btn btn-secondary remove-selected"
                type="button"
                id="derivative-switch"
                wire:click.prevent="$set('derivativeInput' , {{!$derivativeInput}})"
        >
            @if ( $derivativeInput )
                <i class="fa fa-terminal"></i>
            @else
                <i class="fa fa-list-alt"></i>
            @endif
        </button>
    </div>
</div>
{{-- Engine (Required) --}}
<div class="form-group row">
    <label class="col-md-2 col-form-label" for="vehicle_engine"><i class="fa fa-asterisk fa-fw text-danger" aria-hidden="true"></i>Engine</label>
    @if ( $engineInput )
        <div class="col-md-6">
            <div class="input-group mb-3">
                @error('engine')
                <div class="input-group-prepend">
                    <label class="input-group-text bg-danger text-white" for="inputGroupSelectEngine"><i class="fa fa-exclamation-triangle"></i></label>
                </div>
                @enderror
                <select wire:model="engine" class="custom-select" id="inputGroupSelectEngine">
                    <option selected>Choose...</option>
                    @foreach ($engines as $vehicle_engine)
                        <option value="{{ $vehicle_engine->name }}">{{ $vehicle_engine->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    @else
        <div class="col-md-6 mb-3">
            <input wire:model="engine" type="text" name="vehicle_engine" id="vehicle_engine"
                   class="form-control" autocomplete="off" placeholder="e.g. 1.6 Litre"/>
        </div>
    @endif

    <div class="col-md-2">
        <button class="btn btn-secondary remove-selected"
                type="button"
                id="engine-switch"
                wire:click.prevent="$set('engineInput' , {{!$engineInput}})"
        >
            @if ( $engineInput )
                <i class="fa fa-terminal"></i>
            @else
                <i class="fa fa-list-alt"></i>
            @endif
        </button>
    </div>
</div>
{{-- Transmission (Required) --}}
<div class="form-group row">
    <label class="col-md-2 col-form-label" for="vehicle_trans"><i class="fa fa-asterisk fa-fw text-danger" aria-hidden="true"></i>Transmission</label>
    @if ( $transmissionInput )
        <div class="col-md-6">
            <div class="input-group mb-3">
                @error('transmission')
                <div class="input-group-prepend">
                    <label class="input-group-text bg-danger text-white" for="inputGroupSelectTransmission"><i class="fa fa-exclamation-triangle"></i></label>
                </div>
                @enderror
                <select wire:model="transmission" class="custom-select" id="inputGroupSelectTransmission">
                    <option selected>Choose...</option>
                    @foreach ($transmissions as $vehicle_trans)
                        <option value="{{ $vehicle_trans->name }}">{{ $vehicle_trans->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    @else
        <div class="col-md-6 mb-3">
            <input wire:model="transmission"
                   type="text"
                   name="vehicle_trans"
                   id="vehicle_trans"
                   class="form-control"
                   autocomplete="off"
                   placeholder="e.g. Manual"/>
        </div>
    @endif
    <div class="col-md-2">
        <button class="btn btn-secondary remove-selected"
                type="button"
                id="transmission-switch"
                wire:click.prevent="$set('transmissionInput' , {{!$transmissionInput}})"
        >
            @if ( $transmissionInput )
                <i class="fa fa-terminal"></i>
            @else
                <i class="fa fa-list-alt"></i>
            @endif
        </button>
    </div>
</div>
{{-- Fuel Type --}}
<div class="form-group row">
    <label class="col-md-2 col-form-label" for="vehicle_trans"><i class="fa fa-asterisk fa-fw text-danger" aria-hidden="true"></i>Fuel Type</label>
    @if ( $fuelInput )
        <div class="col-md-6">
            <div class="input-group mb-3">
                @error('fuel_type')
                <div class="input-group-prepend">
                    <label class="input-group-text bg-danger text-white" for="inputGroupSelectFuel"><i class="fa fa-exclamation-triangle"></i></label>
                </div>
                @enderror
                <select wire:model="fuel_type" class="custom-select" id="inputGroupSelectFuel">
                    <option selected>Choose...</option>
                    @foreach ($fuel_types as $vehicle_fuel_type)
                        <option value="{{ $vehicle_fuel_type->name }}">{{ $vehicle_fuel_type->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    @else
        <div class="col-md-6 mb-3">
            <input wire:model="fuel_type"
                   type="text"
                   name="vehicle_fuel"
                   id="vehicle_fuel"
                   class="form-control"
                   autocomplete="off"
                   placeholder="e.g. Petrol"/>
        </div>
    @endif
    <div class="col-md-2">
        <button class="btn btn-secondary remove-selected"
                type="button"
                id="fuel-switch"
                wire:click.prevent="$set('fuelInput' , {{!$fuelInput}})"
        >
            @if ( $fuelInput )
                <i class="fa fa-terminal"></i>
            @else
                <i class="fa fa-list-alt"></i>
            @endif
        </button>
    </div>
</div>
{{-- Colour --}}
<div class="form-group row">
    <label class="col-md-2 col-form-label" for="vehicle_colour"><i class="fa fa-asterisk fa-fw text-danger" aria-hidden="true"></i>Colour</label>
    @if ( $colourInput )
        <div class="col-md-6">
            <div class="input-group mb-3">
                @error('colour')
                <div class="input-group-prepend">
                    <label class="input-group-text bg-danger text-white" for="inputGroupSelectColour"><i class="fa fa-exclamation-triangle"></i></label>
                </div>
                @enderror
                <select wire:model="colour" class="custom-select" id="inputGroupSelectColour">
                    <option value="">Choose...</option>
                    @foreach ($colours as $vehicle_colour)
                        <option value="{{ $vehicle_colour->name }}">{{ $vehicle_colour->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    @else
        <div class="col-md-6 mb-3">
            <input wire:model="colour"
                   type="text"
                   name="vehicle_colour"
                   id="vehicle_colour"
                   class="form-control"
                   autocomplete="off"

                   placeholder="e.g. Metropolis White"/>
        </div>
    @endif
    <div class="col-md-2">
        <button class="btn btn-secondary remove-selected"
                type="button"
                id="colour-switch"
                wire:click.prevent="$set('colourInput' , {{!$colourInput}})"
        >
            @if ( $colourInput )
                <i class="fa fa-terminal"></i>
            @else
                <i class="fa fa-list-alt"></i>
            @endif
        </button>
    </div>
</div>
{{-- Trim --}}
<div class="form-group row">
    <label class="col-md-2 col-form-label" for="vehicle_trim"><i class="fa fa-asterisk fa-fw text-danger" aria-hidden="true"></i>Trim</label>
    @if ( $trimInput )
        <div class="col-md-6">
            <div class="input-group mb-3">
                @error('trim')
                <div class="input-group-prepend">
                    <label class="input-group-text bg-danger text-white" for="inputGroupSelectTrim"><i class="fa fa-exclamation-triangle"></i></label>
                </div>
                @enderror
                <select wire:model="trim" class="custom-select" id="inputGroupSelectTrim">
                    <option value="">Choose...</option>
                    @foreach ($trims as $vehicle_trim)
                        <option value="{{ $vehicle_trim->name }}">{{ $vehicle_trim->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    @else
        <div class="col-md-6 mb-3">
            <input wire:model="trim"
                   type="text"
                   name="vehicle_trim"
                   id="vehicle_trim"
                   class="form-control"
                   autocomplete="off"
                   placeholder="e.g. Petrol"/>
        </div>
    @endif
    <div class="col-md-2">
        <button class="btn btn-secondary remove-selected"
                type="button"
                id="trim-switch"
                wire:click.prevent="$set('trimInput' , {{!$trimInput}})"
        >
            @if ( $trimInput )
                <i class="fa fa-terminal"></i>
            @else
                <i class="fa fa-list-alt"></i>
            @endif
        </button>
    </div>
</div>
{{-- Chassis Prefix --}}
<div class="form-group row">
    <label class="col-md-2 col-form-label" for="chassis_prefix">Chassis Prefix</label>
    <div class="col-md-6">
        <input wire:model="chassis_prefix" type="text" name="chassis_prefix" id="chassis_prefix" class="form-control"
               autocomplete="off" placeholder="e.g. WF0E"/>
    </div>
</div>
{{-- Chassis--}}
<div class="form-group row">
    <label class="col-md-2 col-form-label" for="chassis">Chassis</label>
    <div class="col-md-6">
        <input wire:model="chassis" type="text" name="chassis" id="chassis" class="form-control"
               autocomplete="off" placeholder="e.g. WF0EXXTTGEJG05509"/>
    </div>
</div>
{{-- Vehicle Status --}}
<div class="form-group row">
    <label class="col-md-2 col-form-label" for="vehicle_status"><i class="fa fa-asterisk fa-fw text-danger" aria-hidden="true"></i> Vehicle Status</label>
    <div class="col-md-6">
        <div class="input-group">
            @error('status')
            <div class="input-group-prepend">
                <label class="input-group-text bg-danger text-white" for="inputGroupSelectStatus"><i class="fa fa-exclamation-triangle"></i></label>
            </div>
            @enderror
            <select wire:model="status" class="custom-select" name="vehicle_status" id="inputGroupSelectStatus">
                <option value="">Please Select Status</option>
                <option value="4">
                    Factory Order
                </option>
                <option value="1">
                    In Stock
                </option>
                <option value="3">
                    Ready for Delivery
                </option>
                <option value="6">
                    Delivery Booked
                </option>
                <option value="7">
                    Completed Orders
                </option>
                <option value="10">
                    Europe VHC
                </option>
                <option value="11">
                    UK VHC
                </option>
            </select>
        </div>
    </div>
</div>
{{-- Due Date --}}
<div class="form-group row">
    <label class="col-md-2 col-form-label" for="due_date">Due Date to Dealer</label>
    <div class="col-md-6">
        <input wire:model="due_date" type="text" name="due_date" id="due_date" class="form-control"
               autocomplete="off" placeholder="e.g. 30/03/2019"
               onchange="this.dispatchEvent(new InputEvent('input'))"
        />
    </div>
</div>
{{-- Delivery Date --}}
<div class="form-group row">
    <label class="col-md-2 col-form-label" for="delivery_date">Delivery Date</label>
    <div class="col-md-6">
        <input type="text" name="delivery_date" id="delivery_date" class="form-control"
               autocomplete="off" placeholder="e.g. 20/06/1993" wire:model="delivery_date"
               onchange="this.dispatchEvent(new InputEvent('input'))"/>
    </div>
</div>
{{-- Model Year --}}
<div class="form-group row">
    <label class="col-md-2 col-form-label" for="model_year">Model Year</label>
    <div class="col-md-6">
        <input type="text" name="model_year" id="model_year" class="form-control"
               autocomplete="off" placeholder="e.g. 2020 .50" wire:model="model_year"
               onchange="this.dispatchEvent(new InputEvent('input'))"/>
    </div>
</div>
{{-- Vehicle Registered On --}}
<div class="form-group row">
    <label class="col-md-2 col-form-label" for="vehicle_registered_on">Vehicle
        Registered</label>
    <div class="col-md-6">
        <input type="text"
               name="vehicle_registered_on"
               id="vehicle_registered_on"
               class="form-control"
               autocomplete="off"
               placeholder="e.g. 14/05/2020"
               wire:model="registered_date"
               onchange="this.dispatchEvent(new InputEvent('input'))"
        />
    </div>
</div>
{{-- Show in Ford Pipline --}}
<div class="form-group row">
    <label for="show_in_ford_pipeline" class="col-md-2 col-form-label">Show in Ford
        Pipeline</label>
    <div class="col-md-6">
        <select wire:model="ford_pipeline" name="show_in_ford_pipeline" id="show_in_ford_pipeline"
                class="form-control">
            <option value="0">No
            </option>
            <option value="1">Yes
            </option>
        </select>
    </div>
</div>
