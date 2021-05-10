<div>
    <form wire:submit.prevent="orderFormSubmit" method="POST" enctype="multipart/form-data">
        @csrf
        @if ($successMsg)
            <div class="alert alert-success" role="alert">
                {{$successMsg}}
            </div>
        @endif
        <div class="card shadow mb-4">
            <!-- Card Header -->
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-l-blue">Registration Information</h6>
            </div>
            <!-- Card Body -->
            <div class="card-body">

                <div class="mb-5">
                    <button type="button" class="btn btn-primary" wire:click.debounce.300ms="$set('newCustomer', true)">New Customer</button>
                    <button type="button" class="btn btn-primary" wire:click.debounce.300ms="$set('newCustomer', false)">Existing Customer</button>
                </div>

                <!-- Group the Customer Creation -->


                @if($newCustomer)

                    <div id="orderCreateNewCustomer" class="order-tab-group" data-tab="orderCreateNewCustomer">
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label" for="customer_name">Customer Name</label>
                            <div class="col-md-6">
                                <input wire:model="name" type="text" name="customer_name" id="customer_name" class="form-control" placeholder="e.g. Ted Moseby" />
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 col-form-label" for="company_name">Company Name</label>
                            <div class="col-md-6">
                                <input type="text" name="company_name" id="company_name" class="form-control" placeholder="e.g. Mosbius Designs" wire:model="company"/>
                            </div>
                        </div>

                        <div class="form-group row">
                            <Label class="col-md-2 col-form-label" for="preferred_name">Preferred name to show</label>
                            <div class="col-md-6">
                                <select name="preferred_name" id="preferred_name" class="form-control" wire:model="preferred">
                                    <option value="customer">
                                        Customer Name
                                    </option>
                                    <option value="company">
                                        Company Name
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
            </div>

                @else

                    <div class="form-group row order-tab-group">

                        <label for="customer" class="col-md-2 col-form-label">Select Existing Customer</label>
                        <div class="col-md-6">
                            <select wire:model="customer_id" name="preferred_name" id="preferred_name" class="form-control" wire:model="preferred">
                                <option value="">Select Customer</option>
                                @foreach ( $customers as $customer )
                                    <option value="{{ $customer->id }}">
                                        {{ $customer->customer_name }}
                                        @if ( $customer->customer_name && $customer->company_name )/@endif
                                        {{$customer->company_name}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
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
                    <div class="col-md-4">
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

                    </div>
                    <div class="col-md-4">


                        <input type="text"
                               name="vehicle_make"
                               id="vehicle_make"
                               class="form-control"
                               autocomplete="off"
                               placeholder="e.g. Ford"
                               @unless ( is_null( $make ) )
                               value="{{$manufacturers[$make]['name']}}"
                               @endunless
                                wire:model.lazy="newmake"
                        />

                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-secondary remove-selected"
                                type="button"
                                id="make-remove"
                                wire:click.prevent="$set('make' , null)"
                        >
                            <i class="fa fa-times"></i>
                        </button>
                    </div>

                </div>
                {{-- Model (Required) --}}
                <div class="form-group row">
                    <label class="col-md-2 col-form-label" for="vehicle_model"><i class="fa fa-asterisk fa-fw text-danger" aria-hidden="true"></i>Vehicle Model</label>
                    <div class="col-md-4">
                        <div class="input-group mb-3">
                            @error('model')
                            <div class="input-group-prepend">
                                <label class="input-group-text bg-danger text-white" for="inputGroupSelectModel"><i class="fa fa-exclamation-triangle"></i></label>
                            </div>
                            @enderror
                            <select class="form-control value-change" @unless ( !is_null( $make ) ) disabled @endunless field-parent="vehicle_model" wire:model="model">

                                @unless ( is_null( $make ) )

                                    <option value="" selected>Choose...</option>
                                    @foreach(json_decode($manufacturers[$make]['models']) as $model)
                                        <option value="{{$model}}">{{$model}}</option>
                                    @endforeach

                                @endunless
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">

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

                    </div>




                    <div class="col-md-2">
                        <button class="btn btn-secondary remove-selected"
                                type="button"
                                id="make-remove"
                                wire:click.prevent="$set('model' , null)"
                        >
                            <i class="fa fa-times"></i>
                        </button>
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
                    <div class="col-md-6">
                        <input wire:model="registration" type="text" name="vehicle_reg" id="vehicle_reg" class="form-control" placeholder="e.g. WM63 NKZ"/>
                    </div>
                </div>
                {{-- Derivative (Required) --}}
                <div class="form-group row">
                    <label class="col-md-2 col-form-label" for="vehicle_derivative"><i class="fa fa-asterisk fa-fw text-danger" aria-hidden="true"></i>Derivative</label>
                    <div class="col-md-4">
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
                    <div class="col-md-4">
                        <input wire:model="derivative" type="text" name="vehicle_derivative" id="vehicle_derivative" class="form-control" autocomplete="off" placeholder="e.g. ST-Line X PHEV"/>
                    </div>



                    <div class="col-md-2">
                        <button class="btn btn-secondary remove-selected"
                                type="button"
                                id="derivative-remove"
                                wire:click.prevent="$set('derivative' , null)"
                        >
                            <i class="fa fa-times"></i>
                        </button>
                    </div>
                </div>
                {{-- Engine (Required) --}}
                <div class="form-group row">
                    <label class="col-md-2 col-form-label" for="vehicle_engine"><i class="fa fa-asterisk fa-fw text-danger" aria-hidden="true"></i>Engine</label>
                    <div class="col-md-4">
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
                    <div class="col-md-4">
                        <input wire:model="engine" type="text" name="vehicle_engine" id="vehicle_engine"
                               class="form-control" autocomplete="off" placeholder="e.g. 1.6 Litre"/>
                    </div>

                    <div class="col-md-2">
                        <button class="btn btn-secondary remove-selected"
                                type="button"
                                id="make-remove"
                                wire:click.prevent="$set('engine' , null)"
                        >
                            <i class="fa fa-times"></i>
                        </button>
                    </div>
                </div>
                {{-- Transmission (Required) --}}
                <div class="form-group row">
                    <label class="col-md-2 col-form-label" for="vehicle_trans"><i class="fa fa-asterisk fa-fw text-danger" aria-hidden="true"></i>Transmission</label>
                    <div class="col-md-4">
                        <div class="input-group mb-3">
                            @error('transmission')
                            <div class="input-group-prepend">
                                <label class="input-group-text bg-danger text-white" for="inputGroupSelectTransmission"><i class="fa fa-exclamation-triangle"></i></label>
                            </div>
                            @enderror
                            <select wire:model="engine" class="custom-select" id="inputGroupSelectTransmission">
                                <option selected>Choose...</option>
                                @foreach ($transmissions as $vehicle_trans)
                                    <option value="{{ $vehicle_trans->name }}">{{ $vehicle_trans->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <input wire:model="transmission"
                               type="text"
                               name="vehicle_trans"
                               id="vehicle_trans"
                               class="form-control"
                               autocomplete="off"
                               placeholder="e.g. Manual"/>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-secondary remove-selected"
                                type="button"
                                id="make-remove"
                                wire:click.prevent="$set('transmission' , null)"
                        >
                            <i class="fa fa-times"></i>
                        </button>
                    </div>
                </div>
                {{-- Fuel Type --}}
                <div class="form-group row">
                    <label class="col-md-2 col-form-label" for="vehicle_trans"><i class="fa fa-asterisk fa-fw text-danger" aria-hidden="true"></i>Fuel Type</label>
                    <div class="col-md-4">
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
                    <div class="col-md-4">
                        <input wire:model="fuel_type"
                               type="text"
                               name="vehicle_fuel"
                               id="vehicle_fuel"
                               class="form-control"
                               autocomplete="off"
                               placeholder="e.g. Petrol"/>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-secondary remove-selected"
                                type="button"
                                id="fuel-remove"
                                wire:click.prevent="$set('fuel_type' , null)"
                        >
                            <i class="fa fa-times"></i>
                        </button>
                    </div>
                </div>


                {{-- Colour --}}
                <div class="form-group row">
                    <label class="col-md-2 col-form-label" for="vehicle_colour"><i class="fa fa-asterisk fa-fw text-danger" aria-hidden="true"></i>Colour</label>
                    <div class="col-md-4">
                        <div class="input-group mb-3">
                            @error('colour')
                            <div class="input-group-prepend">
                                <label class="input-group-text bg-danger text-white" for="inputGroupSelectColour"><i class="fa fa-exclamation-triangle"></i></label>
                            </div>
                            @enderror
                            <select wire:model="colour" class="custom-select" id="inputGroupSelectColour">
                                <option value="">Please Select</option>
                                @foreach ($colours as $vehicle_colour)
                                    <option value="{{ $vehicle_colour->name }}">{{ $vehicle_colour->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <input wire:model="fuel_type"
                               type="text"
                               name="vehicle_colour"
                               id="vehicle_colour"
                               class="form-control"
                               autocomplete="off"
                               
                               placeholder="e.g. Metropolis White"/>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-secondary remove-selected"
                                type="button"
                                id="colour-remove"
                                wire:click.prevent="$set('colour' , null)"
                        >
                            <i class="fa fa-times"></i>
                        </button>
                    </div>
                </div>

                {{-- Trim --}}
                <div class="form-group row">
                    <label class="col-md-2 col-form-label" for="vehicle_trim"><i class="fa fa-asterisk fa-fw text-danger" aria-hidden="true"></i>Trim</label>
                    <div class="col-md-4">
                        <div class="input-group mb-3">
                            @error('fuel_type')
                            <div class="input-group-prepend">
                                <label class="input-group-text bg-danger text-white" for="inputGroupSelectColour"><i class="fa fa-exclamation-triangle"></i></label>
                            </div>
                            @enderror
                            <select wire:model="colour" class="custom-select" id="inputGroupSelectColour">
                                <option value="">Please Select</option>
                                @foreach ($trims as $vehicle_trim)
                                    <option value="{{ $vehicle_trim->name }}">{{ $vehicle_trim->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <input wire:model="trim"
                               type="text"
                               name="vehicle_trim"
                               id="vehicle_trim"
                               class="form-control"
                               autocomplete="off"
                               placeholder="e.g. Petrol"/>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-secondary remove-selected"
                                type="button"
                                id="colour-remove"
                                wire:click.prevent="$set('trim' , null)"
                        >
                            <i class="fa fa-times"></i>
                        </button>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-2 col-form-label" for="broker">Broker
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
                                <label class="input-group-text bg-warning text-white" for="inputGroupSelectBroker"><i class="fa fa-exclamation-triangle"></i></label>
                            </div>
                            @enderror
                            <select wire:model="broker" class="custom-select" id="inputGroupSelectBroker">
                                <option selected>Choose...</option>
                                @foreach ($brokers as $broker)
                                    <option value="{{ $broker->id }}">{{ $broker->company_name }}</option>
                                @endforeach
                            </select>
                        </div>


                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-2 col-form-label" for="broker_order_ref">Broker Order Ref</label>
                    <div class="col-md-6">
                        <input wire:model="broker_ref" type="text" name="broker_order_ref" id="broker_order_ref"
                               class="form-control" autocomplete="off" placeholder="e.g. Q1780361"/>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-2 col-form-label" for="order_ref">Order Ref</label>
                    <div class="col-md-6">
                        <input wire:model="order_ref" type="text" name="order_ref" id="order_ref" class="form-control"
                               autocomplete="off" placeholder="e.g. K0047"/>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-2 col-form-label" for="chassis_prefix">Chassis Prefix</label>
                    <div class="col-md-6">
                        <input wire:model="chassis_prefix" type="text" name="chassis_prefix" id="chassis_prefix" class="form-control"
                               autocomplete="off" placeholder="e.g. WF0E"/>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-2 col-form-label" for="chassis">Chassis</label>
                    <div class="col-md-6">
                        <input wire:model="chassis" type="text" name="chassis" id="chassis" class="form-control"
                               autocomplete="off" placeholder="e.g. WF0EXXTTGEJG05509"/>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-2 col-form-label" for="vehicle_status">Vehicle Status <small
                                class="text-danger">(required)</small></label>
                    <div class="col-md-6">
                        <select wire:model="status" class="form-control" name="vehicle_status" id="vehicle_status">
                            <option value="">Please Select Status</option>
                            <option value="1">
                                In Stock
                            </option>
                            <option value="2">
                                Orders Placed
                            </option>
                            <option value="3">
                                Ready for Delivery
                            </option>
                            <option value="4">
                                Factory Order
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

                <div class="form-group row">
                    <label class="col-md-2 col-form-label" for="due_date">Due Date to Dealer</label>
                    <div class="col-md-6">
                        <input wire:model="due_date" type="text" name="due_date" id="due_date" class="form-control"
                               autocomplete="off" placeholder="e.g. 30/03/2019"
                               onchange="this.dispatchEvent(new InputEvent('input'))"
                        />
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-2 col-form-label" for="delivery_date">Delivery Date</label>
                    <div class="col-md-6">
                        <input type="text" name="delivery_date" id="delivery_date" class="form-control"
                               autocomplete="off" placeholder="e.g. 20/06/1993" wire:model="delivery_date"
                               onchange="this.dispatchEvent(new InputEvent('input'))"/>
                    </div>
                </div>
                @if(Auth()->user()->role != 'broker')
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label" for="model_year">Model Year</label>
                        <div class="col-md-6">
                            <input type="text" name="model_year" id="model_year" class="form-control"
                                   autocomplete="off" placeholder="e.g. 2020 .50" wire:model="model_year"
                                   onchange="this.dispatchEvent(new InputEvent('input'))"/>
                        </div>
                    </div>
                @endif
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
                <h6 class="m-0 font-weight-bold text-l-blue">Factory Options</h6>
            </div>
            <!-- Card Body -->
            <div class="card-body">
                <div class="form-group row">
                    <label class="col-md-2 col-form-label">Factory Options</label>
                    <div class="col-md-4 factory-row">
                        <div class="row">
                            <div class="col-md-5">
                                <input wire:model="factory_fit_name_manual_add" type="text" class="form-control" placeholder="e.g. LED Lights"/>
                            </div>
                            <div class="col-md-5">
                                <input wire:model="factory_fit_price_manual_add" type="number" step=".01" class="form-control"
                                       placeholder="e.g. 189.99"/>
                            </div>
                        </div>
                        <div class="add-factory-con mt-4">
                            <button wire:click="newFactoryFit" class="btn btn-sm btn-secondary" type="button">
                                Add New Option
                            </button>
                        </div>
                    </div>
                    <div class="col-md-4 offset-2">
                        <select wire:model.lazy="factory_fit_options" multiple>
                            @foreach ($factory_options as $factory_option)

                                <option data-cost="{{ $factory_option->option_price }}" value="{{ $factory_option->id }}" >{{ $factory_option->option_name }} -
                                    &pound;{{ $factory_option->option_price }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <!-- Card Header -->
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-l-blue">Dealer Fit Options</h6>
            </div>
            <!-- Card Body -->
            <div class="card-body">
                <div class="form-group row mb-5">
                    <label class="col-md-2 col-form-label">Dealer Fit Options</label>
                    <div class="col-md-4">
                        <div class="row dealer-row">
                            <div class="col-md-5">
                                <input wire:model="dealer_fit_name_manual_add" type="text" class="form-control" placeholder="e.g. LED Lights"/>
                            </div>
                            <div class="col-md-5">
                                <input wire:model="dealer_fit_price_manual_add" type="number" step=".01" class="form-control"
                                       placeholder="e.g. 20.99"/>
                            </div>
                        </div>
                        <div class="add-dealer-con mt-4">
                            <button wire:click="newDealerFit" class="btn btn-sm btn-secondary" id="add-dealer-option" type="button">
                                Add New Option
                            </button>
                        </div>
                    </div>
                    <div class="col-md-4 offset-2">
                        <select wire:model.lazy="dealer_fit_options" multiple>
                            @foreach ($dealer_options as $dealer_option)
                                <option data-cost="{{ $dealer_option->option_price }}" value="{{ $dealer_option->id }}">
                                    {{ $dealer_option->option_name }} - &pound;{{ $dealer_option->option_price }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

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

                <div class="form-group row">
                    <label for="dealership" class="col-md-2 col-form-label">Registration Company</label>
                    <div class="col-md-6">
                        <select wire:model="registration_company" name="registration_company" id="registration_company" class="form-control">
                            <option value="">Select Registration Company</option>
                            @foreach ($registration_companies as $company)
                                <option value="{{ $company->id }}"
                                        @if ($company->id == $order_details->registration_company) selected @endif>{{ $company->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="invoice_company" class="col-md-2 col-form-label">Invoice Company</label>
                    <div class="col-md-6">
                        <select wire:model="invoice_company" name="invoice_company" id="invoice_company" class="form-control">
                            <option value="">Select Invoice Company</option>
                            @foreach ($invoice_companies as $company)
                                <option value="{{ $company->id }}"
                                        @if ($company->id == $order_details->invoice_company) selected @endif>{{ $company->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

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
                        <input wire:model="list_price" type="number" name="list_price" id="list_price" step=".01"
                               class="form-control" autocomplete="off" placeholder="e.g. 10985.24"/>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="metallic_paint" class="col-md-2 col-form-label">Metallic Paint (£)</label>
                    <div class="col-md-6">
                        <input wire:model="metallic_paint" type="number" name="metallic_paint" id="metallic_paint" step=".01"
                               class="form-control" autocomplete="off" placeholder="e.g. 389.55"/>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="dealer_discount" class="col-md-2 col-form-label">Dealer Discount (%)</label>
                    <div class="col-md-6">
                        <input wire:model="dealer_discount" type="number" name="dealer_discount" id="dealer_discount" step=".01"
                               class="form-control discount" autocomplete="off" placeholder="e.g. 2.857" />
                    </div>
                </div>

                <div class="form-group row">
                    <label for="manufacturer_discount" class="col-md-2 col-form-label">Manufacturer Discount
                        (%)</label>
                    <div class="col-md-6">
                        <input wire:model="manufacturer_discount" type="number" name="manufacturer_discount" id="manufacturer_discount"
                               step=".01" class="form-control discount" autocomplete="off"
                               placeholder="e.g. 3.879"/>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="total_discount" class="col-md-2 col-form-label">Total Discount (%)</label>
                    <div class="col-md-6">
                        <input wire:model="dealer_discount + manufacturer_discount" type="number" name="total_discount" id="total_discount" class="form-control"
                               placeholder="Dealer + Manufacturer"
                               disabled/>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="manufacturer_delivery_cost" class="col-md-2 col-form-label">Manufacturer
                        Delivery Cost (£)</label>
                    <div class="col-md-6">
                        <input wire:model="manufacturer_delivery_cost" type="number" name="manufacturer_delivery_cost"
                               id="manufacturer_delivery_cost" step=".01" class="form-control invoice-total"
                               autocomplete="off" placeholder="e.g. 88.59"/>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="first_reg_fee" class="col-md-2 col-form-label">1st Reg Fee (£)</label>
                    <div class="col-md-6">
                        <input wire:model="first_reg_fee" type="number" name="first_reg_fee" id="first_reg_fee" step=".01"
                               class="form-control" autocomplete="off" placeholder="e.g. 199.99" />
                    </div>
                </div>

                <div class="form-group row">
                    <label for="rfl_cost" class="col-md-2 col-form-label">RFL (£)</label>
                    <div class="col-md-6">
                        <input wire:model="rfl_cost" type="number" name="rfl_cost" id="rfl_cost" step=".01" class="form-control"
                               autocomplete="off" placeholder="e.g. 85.00"/>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="onward_delivery" class="col-md-2 col-form-label">Onward Delivery (£)</label>
                    <div class="col-md-6">
                        <input wire:model="onward_delivery" type="number" name="onward_delivery" id="onward_delivery" step=".01"
                               class="form-control invoice-total" autocomplete="off" placeholder="e.g. 85.00"/>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="invoice_funder_for" class="col-md-2 col-form-label">Invoice Funder For (£)</label>
                    <div class="col-md-6">
                        <input wire:model="invoice_funder_for" type="number" name="invoice_funder_for" id="invoice_funder_for" step=".01"
                               class="form-control" autocomplete="off" placeholder="e.g. 85.00"/>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="invoice_value" class="col-md-2 col-form-label">Invoice Value (£)</label>
                    <div class="col-md-6">
                        <input type="text" name="invoice_value" id="invoice_value" class="form-control" disabled/>
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
            <!-- Card Header -->
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-l-blue">Invoicing Information</h6>
            </div>
            <!-- Card Body -->
            <div class="card-body">
                <div class="form-group row">
                    <label for="invoice_finance" class="col-md-2 col-form-label">Commission to Finance Company (£)</label>
                    <div class="col-md-6">
                        <input wire:model="invoice_finance" type="number" name="invoice_finance" id="invoice_finance" step=".01"
                               class="form-control" autocomplete="off" placeholder="e.g. 134.25"/>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="invoice_finance_number" class="col-md-2 col-form-label">Commission to Finance Company Invoice Number</label>
                    <div class="col-md-6">
                        <input wire:model="invoice_finance_number" type="text" name="invoice_finance_number" id="invoice_finance_number"
                               class="form-control" autocomplete="off" placeholder="e.g. CF1234" />
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-2 col-form-label" for="finance_commission_paid">Finance Commission Pay Date</label>
                    <div class="col-md-6">
                        <input wire:model="finance_commission_paid" type="text" name="finance_commission_paid" id="finance_commission_paid"
                               class="form-control" autocomplete="off" placeholder="e.g. 30/03/2019" onchange="this.dispatchEvent(new InputEvent('input'))" />
                    </div>
                </div>
                <div class="form-group row">
                    <label for="invoice_broker" class="col-md-2 col-form-label">Invoice to Broker (£)</label>
                    <div class="col-md-6">
                        <input wire:model="invoice_value_to_broker" type="number" name="invoice_broker" id="invoice_broker" step=".01"
                               class="form-control" autocomplete="off" placeholder="e.g. 85.71" />
                    </div>
                </div>
                <div class="form-group row">
                    <label for="invoice_broker_number" class="col-md-2 col-form-label">Invoice to Broker Invoice Number</label>
                    <div class="col-md-6">
                        <input wire:model="invoice_broker_number" type="text" name="invoice_broker_number" id="invoice_broker_number"
                               class="form-control" autocomplete="off" placeholder="e.g. BO568"/>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2 col-form-label" for="invoice_broker_paid">Broker Invoice Pay Date</label>
                    <div class="col-md-6">
                        <input wire:model="invoice_broker_paid" type="text" name="invoice_broker_paid" id="invoice_broker_paid"
                               class="form-control" autocomplete="off" placeholder="e.g. 30/03/2019" onchange="this.dispatchEvent(new InputEvent('input'))" />
                    </div>
                </div>
                <div class="form-group row">
                    <label for="commission_broker" class="col-md-2 col-form-label">Commission to Broker (£)</label>
                    <div class="col-md-6">
                        <input wire:model="commission_broker" type="number" name="commission_broker" id="commission_broker" step=".01"
                               class="form-control" autocomplete="off" placeholder="e.g. 420.81"/>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="commission_broker_number" class="col-md-2 col-form-label">Commission to Broker Invoice Number</label>
                    <div class="col-md-6">
                        <input wire:model="commission_broker_number" type="text" name="commission_broker_number" id="commission_broker_number"
                               class="form-control" autocomplete="off" placeholder="e.g. CB4097"/>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-2 col-form-label" for="commission_broker_paid">Broker Commission Pay Date</label>
                    <div class="col-md-6">
                        <input wire:model="commission_broker_paid" type="text" name="commission_broker_paid" id="commission_broker_paid"
                               class="form-control" autocomplete="off" placeholder="e.g. 30/03/2019" onchange="this.dispatchEvent(new InputEvent('input'))"/>
                    </div>
                </div>
            </div>
            <!-- Card Header -->
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-l-blue">Delivery Details</h6>
            </div>
            <!-- Card Body -->
            <div class="card-body">
                <div class="form-group row">
                    <label for="customer_phone" class="col-md-2 col-form-label">Customer Phone Number</label>
                    <div class="col-md-6">
                        <input wire:model="customer_phone" type="text" name="customer_phone" id="customer_phone"
                               class="form-control" autocomplete="off" placeholder="e.g. 07900 000 000"/>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="delivery_address_1" class="col-md-2 col-form-label">Address Line 1</label>
                    <div class="col-md-6">
                        <input wire:model="delivery_address_1" type="text" name="delivery_address_1" id="delivery_address_1"
                               class="form-control" autocomplete="off" placeholder="e.g. 20 Saturn Road"/>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="delivery_address_2" class="col-md-2 col-form-label">Address Line 2</label>
                    <div class="col-md-6">
                        <input wire:model="delivery_address_2" type="text" name="delivery_address_2" id="delivery_address_2"
                               class="form-control" autocomplete="off" placeholder="Optional"/>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="delivery_town" class="col-md-2 col-form-label">Town</label>
                    <div class="col-md-6">
                        <input wire:model="delivery_town" type="text" name="delivery_town" id="delivery_town" class="form-control"
                               autocomplete="off" placeholder="e.g. Blisworth"/>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="delivery_city" class="col-md-2 col-form-label">City</label>
                    <div class="col-md-6">
                        <input wire:model="delivery_city" type="text" name="delivery_city" id="delivery_city" class="form-control"
                               autocomplete="off" placeholder="e.g. Northampton"/>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="delivery_county" class="col-md-2 col-form-label">County</label>
                    <div class="col-md-6">
                        <input wire:model="delivery_county" type="text" name="delivery_county" id="delivery_county" class="form-control"
                               autocomplete="off" placeholder="e.g. Northamptonshire"/>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="delivery_postcode" class="col-md-2 col-form-label">Postcode</label>
                    <div class="col-md-6">
                        <input wire:model="delivery_postcode" type="text" name="delivery_postcode" id="delivery_postcode"
                               class="form-control" autocomplete="off" placeholder="e.g. NN7 3DB"/>
                    </div>
                </div>
            </div>
            <!-- Card Header -->
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-l-blue">Additional Information</h6>
            </div>
            <!-- Card Body -->
            <div class="card-body">
                <div class="form-group row">
                    <label class="col-md-2 col-form-label" for="comments">Comments</label>
                    <div class="col-md-6">
                        <textarea wire:model="comments" name="comments" id="comments" class="form-control" rows="4"></textarea>
                    </div>
                </div>
                <div class="row">
                    <label class="col-md-2 col-form-label" for="file">Upload Document(s)<br>
                        <small>Allowed file types - JPEG, PNG, PDF, DOC & DOCX</small>
                    </label>
                    <div class="col-md-6">
                        @for($i = 0; $i < $fields; $i++)
                            <input wire:model="attachments"
                                   type="file"
                                   accept=".pdf, applicaion/pdf, image/png, .png, image/jpg, .jpg, image/jpeg, .jpeg, .doc, .docx, application/msword"
                                   name="file"
                                   id="file"/>
                        @endfor
                    </div>
                    <div class="form-group">
                        <button class="btn btn-primary" wire:click.prevent="handleAddField">Add New File</button>
                    </div>
                </div>
                <div wire:loading wire:target="attachments">Uploading...</div>
                @error('attachments.*') <div class="alert alert-danger">{{ $message }}</div>@enderror
                <ul>
                @foreach($attachments as $key => $attachment)
                    <li>{{$attachment->getClientOriginalName()}} <button wire:click.prevent="removeAttachment({{$key}})">Delete</button></li>
                @endforeach
                </ul>
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
                <button class="btn btn-primary" type="submit">Save Order</button>
            </div>
        </div>
        {{--</div>--}}
    </form>


</div>

@push('custom-scripts')


@endpush