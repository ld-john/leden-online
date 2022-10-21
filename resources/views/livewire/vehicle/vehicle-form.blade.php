<div>
    <form wire:submit.prevent="orderFormSubmit" method="POST" enctype="multipart/form-data">
        <div class="card shadow mb-4">
            @csrf
            @if ($successMsg)
                <div class="alert alert-success" role="alert">
                    {{$successMsg}}
                </div>
            @endif
            <!-- Vehicle Information -->
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-l-blue">Car Information</h6>
            </div>
            <div class="card-body">
                {{-- Make (Required) --}}
                <div class="form-group row">
                    <label class="col-md-2 col-form-label" for="vehicle_make"><i class="fa fa-asterisk fa-fw text-danger" aria-hidden="true"></i>Make</label>
                    <div class="col-md-6">
                        <div class="input-group mb-3">
                            @error('make')
                            <label class="input-group-text bg-danger text-white" for="inputGroupSelectMake"><i class="fa fa-exclamation-triangle"></i></label>
                            @enderror
                            <select wire:model="make" class="form-select" id="inputGroupSelectMake">
                                <option selected>Choose...</option>
                                @foreach ( $manufacturers as $manufacturer )
                                    <option value="{{ $manufacturer->id }}">{{ $manufacturer->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <a class="btn btn-secondary switch-inputs"
                           href="{{ route('meta.make.index') }}"
                           data-toggle="tooltip"
                           title="Add a new Make or Model"
                        >
                            <i class="fa-solid fa-plus"></i>
                        </a>
                    </div>

                </div>
                {{-- Model (Required) --}}
                <div class="form-group row">
                    <label class="col-md-2 col-form-label" for="vehicle_model">
                        <i class="fa fa-asterisk fa-fw text-danger" aria-hidden="true"></i>
                        Vehicle Model
                    </label>
                    <div class="col-md-6">
                        <div class="input-group mb-3">
                            @error('model')
                            <label class="input-group-text bg-danger text-white" for="inputGroupSelectModel"><i class="fa fa-exclamation-triangle"></i></label>
                            @enderror
                            <select class="form-select" @if(count($vehicle_models) === 0) disabled @endif field-parent="vehicle_model" wire:model="model" id="inputGroupSelectModel">
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
                            <label for="inputGroupOrbitNumberText" class="input-group-text bg-danger text-white"><i class="fa fa-exclamation-triangle"></i></label>
                        @enderror
                        <input wire:model="orbit_number" type="text" name="orbit_number" id="orbit_number" class="form-control" autocomplete="off" />
                    </div>
                </div>
                {{-- Ford Order Number --}}
                <div class="form-group row">
                    <label for="order_ref" class="col-md-2 col-form-label">Order Ref</label>
                    <div class="col-md-6">
                        @error('order_ref')
                        <label class="input-group-text bg-danger text-white"><i class="fa fa-exclamation-triangle"></i></label>
                        @enderror
                        <input wire:model="order_ref" type="text" name="order_ref" id="order_ref" class="form-control" autocomplete="off" />
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
                            <select @if(count($types) === 0) disabled @endif wire:model="type" class="form-select" id="inputGroupSelectType">
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
                        <input wire:model="registration" type="text" name="vehicle_reg" id="vehicle_reg" class="form-control" />
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
                            <select wire:model="derivative" @if(count($derivatives) === 0) disabled @endif class="form-select" id="inputGroupSelectDerivatives">
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
                            <select wire:model="engine" @if(count($engines) === 0) disabled @endif class="form-select" id="inputGroupSelectEngine">
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
                            <select wire:model="transmission" @if(count($transmissions) === 0) disabled @endif class="form-select" id="inputGroupSelectTransmission">
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
                    <label class="col-md-2 col-form-label" for="vehicle_fuel"><i class="fa fa-asterisk fa-fw text-danger" aria-hidden="true"></i>Fuel Type</label>
                    <div class="col-md-6">
                        <div class="input-group mb-3">
                            @error('fuel_type')
                            <label class="input-group-text bg-danger text-white" for="inputGroupSelectFuel"><i class="fa fa-exclamation-triangle"></i></label>
                            @enderror
                            <select wire:model="fuel_type" @if(count($fuel_types) === 0) disabled @endif class="form-select" id="inputGroupSelectFuel">
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
                           title="Add or Edit Fuel"
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
                            <select wire:model="colour" @if(count($colours) === 0) disabled @endif class="form-select" id="inputGroupSelectColour">
                                <option selected>Choose...</option>
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
                            <select wire:model="trim" @if(count($trims) === 0) disabled @endif class="form-select" id="inputGroupSelectTrim">
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
                <div class="form-group row">
                    <label for="dealership" class="col-md-2 col-form-label"><i class="fa fa-asterisk fa-fw text-danger" aria-hidden="true"></i> Dealership</label>
                    <div class="col-md-6 mb-3">
                        <select wire:model="dealership" name="dealership" id="dealership" class="form-select">
                            <option value="">Select Dealership</option>
                            @foreach ($dealers as $dealer)
                                <option value="{{ $dealer->id }}">{{ $dealer->company_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                @if($vehicle->ring_fenced_stock)
                    <div class="form-group row">
                        <label for="broker" class="col-md-2 col-form-label">Select Broker</label>
                        <div class="col-md-6 mb-3">
                            <select wire:model="broker" name="broker" id="broker" class="form-select">
                                <option value="">Select Broker</option>
                                @foreach($brokers as $broker)
                                    <option value="{{ $broker->id }}">{{ $broker->company_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                @endif
                <div class="form-group row">
                    <label class="col-md-2 col-form-label" for="chassis_prefix">Chassis Prefix</label>
                    <div class="col-md-6">
                        <input wire:model="chassis_prefix" type="text" name="chassis_prefix" id="chassis_prefix" class="form-control"
                               autocomplete="off" />
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-2 col-form-label" for="chassis">Chassis</label>
                    <div class="col-md-6">
                        <input wire:model="chassis" type="text" name="chassis" id="chassis" class="form-control" autocomplete="off" />
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2 col-form-label" for="vehicle_status"><i class="fa fa-asterisk fa-fw text-danger" aria-hidden="true"></i> Vehicle Status</label>
                    <div class="col-md-6">
                        <div class="input-group">
                            @error('status')
                            <label class="input-group-text bg-danger text-white" for="inputGroupSelectTrim"><i class="fa fa-exclamation-triangle"></i></label>
                            @enderror
                            <select wire:model="status" class="form-control" name="vehicle_status" id="vehicle_status">
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
                            </select>
                        </div>

                    </div>
                </div>
                @if(Auth()->user()->role != 'broker')
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label" for="model_year">Model Year</label>
                        <div class="col-md-6">
                            <input type="text" name="model_year" id="model_year" class="form-control"
                                   autocomplete="off" wire:model="model_year"
                                   onchange="this.dispatchEvent(new InputEvent('input'))"/>
                        </div>
                    </div>
                @endif
                <div class="form-group row">
                    <label class="col-md-2 col-form-label" for="vehicle_registered_on">Vehicle
                        Registered</label>
                    <div class="col-md-6">
                        <div class="input-group">
                            @error('registered_date')
                            <label class="input-group-text bg-danger text-white" for="inputGroupSelectTrim"><i class="fa fa-exclamation-triangle"></i></label>
                            @enderror
                            <input type="date"
                                   name="vehicle_registered_on"
                                   class="form-control"
                                   autocomplete="off"
                                   wire:model="registered_date"
                                   id="vehicle_registered_on"
                            />
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="build_date" class="col-md-2 col-form-label">Proposed Build Date</label>
                    <div class="col-md-6">
                        <div class="input-group">
                            @error('build_date')
                            <label class="input-group-text bg-danger text-white"><i class="fa fa-exclamation-triangle"></i></label>
                            @enderror
                            <input type="date" name="build_date" id="build_date" autocomplete="off" wire:model="build_date" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="due_date" class="col-md-2 col-form-label">Due Date</label>
                    <div class="col-md-6">
                        <div class="input-group">
                            @error('due_date')
                            <label class="input-group-text bg-danger text-white"><i class="fa fa-exclamation-triangle"></i></label>
                            @enderror
                            <input type="date" name="due_date" id="due_date" autocomplete="off" wire:model="due_date" class="form-control">
                        </div>

                    </div>
                </div>
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
            </div>
            <!-- Card Header -->
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-l-blue">Factory Fit Options</h6>
            </div>
            <!-- Card Body -->
            <div class="card-body">
                @include('partials.factory-fit')
            </div>
            <!-- Card Header -->
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-l-blue">Dealer Fit Options</h6>
            </div>
            <!-- Card Body -->
            <div class="card-body">
                @include('partials.dealer-fit')
            </div>
            <!-- Card Header -->
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-l-blue">Cost Breakdown</h6>
            </div>
            <!-- Card Body -->
            <div class="card-body">
                <div class="form-group row">
                    <label for="list_price" class="col-md-2 col-form-label">List Price (£)</label>
                    <div class="col-md-6">
                        <input wire:model="list_price" type="number" name="list_price" id="list_price" step=".01" class="form-control" autocomplete="off" />
                    </div>
                </div>
                <div class="form-group row">
                    <label for="metallic_paint" class="col-md-2 col-form-label">Metallic Paint (£)</label>
                    <div class="col-md-6">
                        <input wire:model="metallic_paint" type="number" name="metallic_paint" id="metallic_paint" step=".01" class="form-control" autocomplete="off" />
                    </div>
                </div>
                <div class="form-group row">
                    <label for="first_reg_fee" class="col-md-2 col-form-label">1st Reg Fee (£)</label>
                    <div class="col-md-6">
                        <input wire:model="first_reg_fee" type="number" name="first_reg_fee" id="first_reg_fee" step=".01" class="form-control" autocomplete="off"  />
                    </div>
                </div>
                <div class="form-group row">
                    <label for="rfl_cost" class="col-md-2 col-form-label">RFL (£)</label>
                    <div class="col-md-6">
                        <input wire:model="rfl_cost" type="number" name="rfl_cost" id="rfl_cost" step=".01" class="form-control" autocomplete="off" />
                    </div>
                </div>
                <div class="form-group row">
                    <label for="onward_delivery" class="col-md-2 col-form-label">Onward Delivery (£)</label>
                    <div class="col-md-6">
                        <input wire:model="onward_delivery" type="number" name="onward_delivery" id="onward_delivery" step=".01" class="form-control invoice-total" autocomplete="off" />
                    </div>
                </div>
                <div class="form-group row">
                    <label for="show_discount" class="col-md-2 col-form-label">Show Discount Applied</label>
                    <div class="col-md-6">
                        <select wire:model="show_discount" name="show_discount" id="show_discount" class="form-control">
                            <option value="0">No
                            </option>
                            <option value="1">Yes
                            </option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="show_offer" class="col-md-2 col-form-label">Show as Offer</label>
                    <div class="col-md-6">
                        <select wire:model="show_offer" name="show_offer" id="show_offer" class="form-control">
                            <option value="0">No
                            </option>
                            <option value="1">Yes
                            </option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="hide_from_broker" class="col-md-2 col-form-label">Hide From Broker
                        List</label>
                    <div class="col-md-6">
                        <select wire:model="hide_from_broker" name="hide_from_broker" id="hide_from_broker" class="form-control">
                            <option value="0">
                                No
                            </option>
                            <option value="1">
                                Yes
                            </option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="hide_from_dealer" class="col-md-2 col-form-label">Hide From Dealer
                        List</label>
                    <div class="col-md-6">
                        <select wire:model="hide_from_dealer" name="hide_from_dealer" id="hide_from_dealer" class="form-control">
                            <option value="0">
                                No
                            </option>
                            <option value="1">
                                Yes
                            </option>
                        </select>
                    </div>
                </div>
            </div>
            @if($errors->count())
                <div class="alert alert-danger alert-dismissible fade show m-5">
                    <h4 class="alert-heading"><i class="fa fa-exclamation-triangle"></i> There are some issues.</h4>
                    <hr>
                    <ul>
                        {!! implode($errors->all('<li>:message</li>')) !!}
                    </ul>
                </div>
            @endif
            <div class="card-footer text-right">
                <button class="btn btn-primary" type="submit">Save Vehicle</button>
            </div>
        </div>
    </form>
</div>
