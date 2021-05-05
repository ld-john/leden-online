@extends('layouts.main', [
    'title' => $title,
    'activePage' => $activePage
    ])

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Content Row -->
        <div class="row justify-content-center">

            <!-- Doughnut Chart -->
            <div class="col-lg-10">
                    <h1 class="h3 mb-4 text-gray-800">Edit Order - #{{ $order_details->id }}</h1>
                @if (!empty(session('successMsg')))
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <strong>{{ session('successMsg') }}</strong>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        </div>
                    </div>
                @endif
                @if (!empty($errorMsg))
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>{{ $errorMsg }}</strong>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        </div>
                    </div>
                @endif
                @if($errors->all())
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-danger" role="alert">
                                @foreach($errors->all() as $message)
                                    @if($message)
                                        {!! $message !!}<br/>
                                    @endif

                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
                <form method="POST" action="{{ route($post_route, $order_details->id) }}" enctype="multipart/form-data">
                    @csrf
                    <div class="card shadow mb-4">
                        <!-- Card Header -->
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-l-blue">Registration Information</h6>
                        </div>
                        <!-- Card Body -->
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-md-2 col-form-label" for="customer_name">Customer Name</label>
                                <div class="col-md-6">
                                    <input type="text" name="customer_name" id="customer_name" class="form-control"
                                           autocomplete="off" placeholder="e.g. Ted Moseby"
                                           value="{{ old('customer_name') ?? $order_details->customer_name }}"/>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-2 col-form-label" for="company_name">Company Name</label>
                                <div class="col-md-6">
                                    <input type="text" name="company_name" id="company_name" class="form-control"
                                           autocomplete="off" placeholder="e.g. Mosbius Designs"
                                           value="{{ old('company_name') ?? $order_details->company_name }}"/>
                                </div>
                            </div>

                            <div class="form-group row">
                                <Label class="col-md-2 col-form-label" for="preferred_name">Preferred name to
                                    show</label>
                                <div class="col-md-6">
                                    <select name="preferred_name" id="preferred_name" autocomplete="off"
                                            class="form-control">
                                        <option value="customer"
                                                @if ($order_details->preferred_name == 'customer') selected @endif>
                                            Customer Name
                                        </option>
                                        <option value="company"
                                                @if ($order_details->preferred_name == 'company') selected @endif>
                                            Company Name
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <!-- Card Header -->
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-l-blue">Car Information</h6>
                        </div>
                        <!-- Card Body -->
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-md-2 col-form-label" for="vehicle_make">Vehicle Make <small
                                            class="text-danger">(required)</small></label>
                                <div class="col-md-4">
                                    <input type="text" required name="vehicle_make" id="vehicle_make"
                                           class="form-control" autocomplete="off" placeholder="e.g. Ford"
                                           value="{{ old('vehicle_make') ?? $order_details->vehicle_make }}"/>
                                </div>
                                <div class="col-md-4">
                                    <select class="form-control value-change" field-parent="vehicle_make">
                                        <option value="">Please Select</option>
                                        @foreach (Order::getFieldValues('vehicle_make') as $vehicle_make)
                                            <option value="{{ $vehicle_make }}">{{ $vehicle_make }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button class="btn btn-secondary remove-selected" type="button" id="make-remove">
                                        Remove Option
                                    </button>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-2 col-form-label" for="vehicle_model">Vehicle Model <small
                                            class="text-danger">(required)</small></label>
                                <div class="col-md-4">
                                    <input type="text" required name="vehicle_model" id="vehicle_model"
                                           class="form-control" autocomplete="off" placeholder="e.g. Fiesta"
                                           value="{{ old('vehicle_model') ?? $order_details->vehicle_model }}"/>
                                </div>
                                <div class="col-md-4">
                                    <select class="form-control value-change" field-parent="vehicle_model">
                                        <option value="">Please Select</option>
                                        @foreach (Order::getFieldValues('vehicle_model') as $vehicle_model)
                                            <option value="{{ $vehicle_model }}">{{ $vehicle_model }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button class="btn btn-secondary remove-selected" type="button" id="model-remove">
                                        Remove Option
                                    </button>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-2 col-form-label" for="vehicle_type">Vehicle Type <small
                                            class="text-danger">(required)</small></label>
                                <div class="col-md-6">
                                    <select class="form-control value-change" name="vehicle_type" id="vehicle_type">
                                        <option value="">Please Select</option>
                                        @foreach (Order::getFieldValues('vehicle_type') as $vehicle_type)
                                            <option value="{{ $vehicle_type }}" @if(old('vehicle_type', $order_details->vehicle_type) == $vehicle_type) selected @endif>{{ $vehicle_type }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-2 col-form-label" for="vehicle_reg">Registration Number</label>
                                <div class="col-md-6">
                                    <input type="text" name="vehicle_reg" id="vehicle_reg" class="form-control"
                                           autocomplete="off" placeholder="e.g. WM63 NKZ"
                                           value="{{ old('vehicle_reg') ?? $order_details->vehicle_reg }}"/>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-2 col-form-label" for="vehicle_derivative">Derivative <small
                                            class="text-danger">(required)</small></label>
                                <div class="col-md-4">
                                    <input type="text" required name="vehicle_derivative" id="vehicle_derivative"
                                           class="form-control" autocomplete="off" placeholder="e.g. ST-Line X PHEV"
                                           value="{{ old('vehicle_derivative') ?? $order_details->vehicle_derivative }}"/>
                                </div>
                                <div class="col-md-4">
                                    <select class="form-control value-change" field-parent="vehicle_derivative">
                                        <option value="">Please Select</option>
                                        @foreach (Order::getFieldValues('vehicle_derivative') as $vehicle_derivative)
                                            <option value="{{ $vehicle_derivative }}">{{ $vehicle_derivative }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button class="btn btn-secondary remove-selected" type="button"
                                            id="derivative-remove">Remove Option
                                    </button>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-2 col-form-label" for="vehicle_engine">Engine <small
                                            class="text-danger">(required)</small></label>
                                <div class="col-md-4">
                                    <input type="text" required name="vehicle_engine" id="vehicle_engine"
                                           class="form-control" autocomplete="off" placeholder="e.g. 1.6 Litre"
                                           value="{{ old('vehicle_engine') ?? $order_details->vehicle_engine }}"/>
                                </div>
                                <div class="col-md-4">
                                    <select class="form-control value-change" field-parent="vehicle_engine">
                                        <option value="">Please Select</option>
                                        @foreach (Order::getFieldValues('vehicle_engine') as $vehicle_engine)
                                            <option value="{{ $vehicle_engine }}">{{ $vehicle_engine }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button class="btn btn-secondary remove-selected" type="button" id="engine-remove">
                                        Remove Option
                                    </button>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-2 col-form-label" for="vehicle_trans">Transmission <small
                                            class="text-danger">(required)</small></label>
                                <div class="col-md-4">
                                    <input type="text" required name="vehicle_trans" id="vehicle_trans"
                                           class="form-control" autocomplete="off" placeholder="e.g. Manual"
                                           value="{{ old('vehicle_trans') ?? $order_details->vehicle_trans }}"/>
                                </div>
                                <div class="col-md-4">
                                    <select class="form-control value-change" field-parent="vehicle_trans">
                                        <option value="">Please Select</option>
                                        @foreach (Order::getFieldValues('vehicle_trans') as $vehicle_trans)
                                            <option value="{{ $vehicle_trans }}">{{ $vehicle_trans }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button class="btn btn-secondary remove-selected" type="button" id="trans-remove">
                                        Remove Option
                                    </button>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-2 col-form-label" for="vehicle_fuel_type">Fuel Type <small
                                            class="text-danger">(required)</small></label>
                                <div class="col-md-4">
                                    <input type="text" required name="vehicle_fuel_type" id="vehicle_fuel_type"
                                           class="form-control" autocomplete="off" placeholder="e.g. Petrol"
                                           value="{{ old('vehicle_fuel_type') ?? $order_details->vehicle_fuel_type }}"/>
                                </div>
                                <div class="col-md-4">
                                    <select class="form-control value-change" field-parent="vehicle_fuel_type">
                                        <option value="">Please Select</option>
                                        @foreach (Order::getFieldValues('vehicle_fuel_type') as $vehicle_fuel_type)
                                            <option value="{{ $vehicle_fuel_type }}">{{ $vehicle_fuel_type }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button class="btn btn-secondary remove-selected" type="button" id="fuel-remove">
                                        Remove Option
                                    </button>
                                </div>
                            </div>

                            {{--<div class="form-group row">
                                <label class="col-md-2 col-form-label" for="vehicle_doors">Doors <small
                                            class="text-danger">(required)</small></label>
                                <div class="col-md-4">
                                    <input type="text" required name="vehicle_doors" id="vehicle_doors"
                                           class="form-control" autocomplete="off" placeholder="e.g. 3 Door"
                                           value="{{ old('vehicle_doors') ?? $order_details->vehicle_doors }}"/>
                                </div>
                                <div class="col-md-4">
                                    <select class="form-control value-change" field-parent="vehicle_doors">
                                        <option value="">Please Select</option>
                                        @foreach (Order::getFieldValues('vehicle_doors') as $vehicle_doors)
                                            <option value="{{ $vehicle_doors }}">{{ $vehicle_doors }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button class="btn btn-secondary remove-selected" type="button" id="doors-remove">
                                        Remove Option
                                    </button>
                                </div>
                            </div>--}}

                            <div class="form-group row">
                                <label class="col-md-2 col-form-label" for="vehicle_colour">Colour <small
                                            class="text-danger">(required)</small></label>
                                <div class="col-md-4">
                                    <input type="text" required name="vehicle_colour" id="vehicle_colour"
                                           class="form-control" autocomplete="off" placeholder="e.g. Pearl White"
                                           value="{{ old('vehicle_colour') ?? $order_details->vehicle_colour }}"/>
                                </div>
                                <div class="col-md-4">
                                    <select class="form-control value-change" field-parent="vehicle_colour">
                                        <option value="">Please Select</option>
                                        @foreach (Order::getFieldValues('vehicle_colour') as $vehicle_colour)
                                            <option value="{{ $vehicle_colour }}">{{ $vehicle_colour }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button class="btn btn-secondary remove-selected" type="button" id="colour-remove">
                                        Remove Option
                                    </button>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-2 col-form-label" for="vehicle_body">Body <small
                                            class="text-danger">(required)</small></label>
                                <div class="col-md-4">
                                    <input type="text" required name="vehicle_body" id="vehicle_body"
                                           class="form-control" autocomplete="off" placeholder="e.g. 5 door"
                                           value="{{ old('vehicle_body') ?? $order_details->vehicle_body }}"/>
                                </div>
                                <div class="col-md-4">
                                    <select class="form-control value-change" field-parent="vehicle_body">
                                        <option value="">Please Select</option>
                                        @foreach (Order::getFieldValues('vehicle_body') as $vehicle_body)
                                            <option value="{{ $vehicle_body }}">{{ $vehicle_body }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button class="btn btn-secondary remove-selected" type="button" id="body-remove">
                                        Remove Option
                                    </button>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-2 col-form-label" for="vehicle_trim">Trim <small
                                            class="text-danger">(required)</small></label>
                                <div class="col-md-4">
                                    <input type="text" required name="vehicle_trim" id="vehicle_trim"
                                           class="form-control" autocomplete="off" placeholder="e.g. Standard"
                                           value="{{ old('vehicle_trim') ?? $order_details->vehicle_trim }}"/>
                                </div>
                                <div class="col-md-4">
                                    <select class="form-control value-change" field-parent="vehicle_trim">
                                        <option value="">Please Select</option>
                                        @foreach (Order::getFieldValues('vehicle_trim') as $vehicle_trim)
                                            <option value="{{ $vehicle_trim }}">{{ $vehicle_trim }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button class="btn btn-secondary remove-selected" type="button" id="colour-remove">
                                        Remove Option
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
                                    <select class="form-control" name="broker" id="broker">
                                        <option value="">Please Select</option>
                                        @foreach (Order::getCompanyInfo('broker') as $broker)
                                            <option value="{{ $broker->id }}"
                                                    @if ($broker->id == $order_details->broker) selected @endif>{{ $broker->company_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-2 col-form-label" for="broker_order_ref">Broker Order Ref</label>
                                <div class="col-md-6">
                                    <input type="text" name="broker_order_ref" id="broker_order_ref"
                                           class="form-control" autocomplete="off" placeholder="e.g. Q1780361"
                                           value="{{ old('broker_order_ref') ?? $order_details->broker_order_ref }}"/>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-2 col-form-label" for="order_ref">Order Ref</label>
                                <div class="col-md-6">
                                    <input type="text" name="order_ref" id="order_ref" class="form-control"
                                           autocomplete="off" placeholder="e.g. K0047"
                                           value="{{ old('order_ref') ?? $order_details->order_ref }}"/>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-2 col-form-label" for="chassis_prefix">Chassis Prefix</label>
                                <div class="col-md-6">
                                    <input type="text" name="chassis_prefix" id="chassis_prefix" class="form-control"
                                           autocomplete="off" placeholder="e.g. WF0E"
                                           value="{{ old('chassis_prefix') ?? $order_details->chassis_prefix }}"/>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-2 col-form-label" for="chassis">Chassis</label>
                                <div class="col-md-6">
                                    <input type="text" name="chassis" id="chassis" class="form-control"
                                           autocomplete="off" placeholder="e.g. WF0EXXTTGEJG05509"
                                           value="{{ old('chassis') ?? $order_details->chassis }}"/>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-2 col-form-label" for="vehicle_status">Vehicle Status <small
                                            class="text-danger">(required)</small></label>
                                <div class="col-md-6">
                                    <select class="form-control" required name="vehicle_status" id="vehicle_status">
                                        <option value="">Please Select Status</option>
                                        <option value="1" @if ($order_details->vehicle_status == 1) selected @endif>In
                                            Stock
                                        </option>
                                        <option value="2" @if ($order_details->vehicle_status == 2) selected @endif>
                                            Orders Placed
                                        </option>
                                        <option value="3" @if ($order_details->vehicle_status == 3) selected @endif>
                                            Ready for Delivery
                                        </option>
                                        <option value="4" @if ($order_details->vehicle_status == 4) selected @endif>
                                            Factory Order
                                        </option>
                                        <option value="6" @if ($order_details->vehicle_status == 6) selected @endif>
                                            Delivery Booked
                                        </option>
                                        <option value="7" @if ($order_details->vehicle_status == 7) selected @endif>
                                            Completed Orders
                                        </option>
                                        <option value="10" @if ($order_details->vehicle_status == 10) selected @endif>
                                            Europe VHC
                                        </option>
                                        <option value="11" @if ($order_details->vehicle_status == 11) selected @endif>UK
                                            VHC
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-2 col-form-label" for="due_date">Due Date to Dealer</label>
                                <div class="col-md-6">
                                    <input type="text" name="due_date" id="due_date" class="form-control"
                                           autocomplete="off" placeholder="e.g. 30/03/2019"
                                           value="@if (is_null($order_details->due_date)){{ old('due_date') }}@else{{ old('due_date') ?? date('d/m/Y', strtotime($order_details->due_date)) }}@endif"/>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-2 col-form-label" for="delivery_date">Delivery Date</label>
                                <div class="col-md-6">
                                    <input type="text" name="delivery_date" id="delivery_date" class="form-control"
                                           autocomplete="off" placeholder="e.g. 20/06/1993"
                                           value="@if (is_null($order_details->delivery_date)){{ old('delivery_date') }}@else{{ old('delivery_date') ?? date('d/m/Y', strtotime($order_details->delivery_date)) }}@endif"/>
                                </div>
                            </div>
                            @if(Auth()->user()->role != 'broker')
                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label" for="model_year">Model Year</label>
                                    <div class="col-md-6">
                                        <input type="text" name="model_year" id="model_year" class="form-control"
                                               autocomplete="off" placeholder="e.g. 2020 .50"
                                               value="{{ old('model_year') ?? $order_details->model_year }}"/>
                                    </div>
                                </div>
                            @endif
                            <div class="form-group row">
                                <label class="col-md-2 col-form-label" for="vehicle_registered_on">Vehicle
                                    Registered</label>
                                <div class="col-md-6">
                                    <input type="text" name="vehicle_registered_on" id="vehicle_registered_on"
                                           class="form-control" autocomplete="off" placeholder="e.g. 14/05/2020"
                                           value="@if (is_null($order_details->vehicle_registered_on)){{ old('vehicle_registered_on') }}@else{{ old('vehicle_registered_on') ?? date('d/m/Y', strtotime($order_details->vehicle_registered_on)) }}@endif"/>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="show_in_ford_pipeline" class="col-md-2 col-form-label">Show in Ford
                                    Pipeline</label>
                                <div class="col-md-6">
                                    <select name="show_in_ford_pipeline" id="show_in_ford_pipeline"
                                            class="form-control">
                                        <option value="0" @if ($order_details->show_discount == 0) selected @endif>No
                                        </option>
                                        <option value="1" @if ($order_details->show_discount == 1) selected @endif>Yes
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
                                            <input type="text" name="factory_option_new[]" id="factory_option"
                                                   class="form-control" placeholder="e.g. LED Lights"/>
                                        </div>
                                        <div class="col-md-5">
                                            <input type="number" name="factory_option_price_new[]" step=".01"
                                                   id="factory_option_price" class="form-control"
                                                   placeholder="e.g. 189.99"/>
                                        </div>
                                    </div>
                                    <div class="add-factory-con mt-4">
                                        <button class="btn btn-sm btn-secondary" id="add-factory-option" type="button">
                                            Add New Option
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-4 offset-2">
                                    <select name="factory_option[]" id="factory_option" multiple data-live-search="true"
                                            class="selectpicker form-control invoice-total" data-style="btn-secondary">
                                        @foreach ($factory_options as $factory_option)

                                            <option data-cost="{{ $factory_option->option_price }}" value="{{ $factory_option->id }}"
                                                    @if (in_array($factory_option->id, $factory_fit_options)) selected @endif>{{ $factory_option->option_name }} -
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
                                            <input type="text" name="dealer_option_new[]" id="dealer_option"
                                                   class="form-control" placeholder="e.g. LED Lights"/>
                                        </div>
                                        <div class="col-md-5">
                                            <input type="number" name="dealer_option_price_new[]" step=".01"
                                                   id="dealer_option_price" class="form-control"
                                                   placeholder="e.g. 20.99"/>
                                        </div>
                                    </div>
                                    <div class="add-dealer-con mt-4">
                                        <button class="btn btn-sm btn-secondary" id="add-dealer-option" type="button">
                                            Add New Option
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-4 offset-2">
                                    <select name="dealer_option[]" id="dealer_option" multiple data-live-search="true"
                                            class="selectpicker form-control invoice-total" data-style="btn-secondary">
                                        @foreach ($dealer_options as $dealer_option)

                                            <option data-cost="{{ $dealer_option->option_price }}" value="{{ $dealer_option->id }}"
                                                    @if (in_array($dealer_option->id, $dealer_fit_options)) selected @endif>{{ $dealer_option->option_name }} -
                                                &pound;{{ $dealer_option->option_price }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="dealership" class="col-md-2 col-form-label">Dealership</label>
                                <div class="col-md-6">
                                    <select name="dealership" id="dealership" class="form-control">
                                        <option value="">Select Dealership</option>
                                        @foreach (Order::getCompanyInfo('dealer') as $dealer)
                                            <option value="{{ $dealer->id }}"
                                                    @if ($dealer->id == $order_details->dealership) selected @endif>{{ $dealer->company_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="dealership" class="col-md-2 col-form-label">Registration Company</label>
                                <div class="col-md-6">
                                    <select name="registration_company" id="registration_company" class="form-control">
                                        <option value="">Select Registration Company</option>
                                        @foreach ($registration_companies as $company)
                                            <option value="{{ $company->id }}"
                                                    @if ($company->id == $order_details->registration_company) selected @endif>{{ $company->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="dealership" class="col-md-2 col-form-label">Invoice Company</label>
                                <div class="col-md-6">
                                    <select name="invoice_company" id="invoice_company" class="form-control">
                                        <option value="">Select Invoice Company</option>
                                        @foreach ($invoice_companies as $company)
                                            <option value="{{ $company->id }}"
                                                    @if ($company->id == $order_details->invoice_company) selected @endif>{{ $company->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>


                            {{--<div class="form-group row">
                                <label for="invoice_to" class="col-md-2 col-form-label">Invoice to</label>
                                <div class="col-md-6">
                                    <input type="text" name="invoice_to" id="invoice_to" class="form-control" autocomplete="off" placeholder="e.g GMB" value="{{ old('invoice_to') ?? $order_details->invoice_to }}" />
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="invoice_to_address" class="col-md-2 col-form-label">Invoice to Address</label>
                                <div class="col-md-6">
                                    <textarea name="invoice_to_address" id="invoice_to_address" class="form-control" rows="4">{{ old('invoice_to_address') ?? $order_details->invoice_to_address  }}</textarea>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="register_to" class="col-md-2 col-form-label">Register to</label>
                                <div class="col-md-6">
                                    <input type="text" name="register_to" id="register_to" class="form-control"autocomplete="off"  placeholder="e.g Barney Stinson" value="{{ old('register_to') ?? $order_details->register_to }}" />
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="register_to_address" class="col-md-2 col-form-label">Registered Address</label>
                                <div class="col-md-6">
                                    <textarea name="register_to_address" id="register_to_address" class="form-control" rows="4">{{ old('register_to_address') ?? $order_details->register_to_address  }}</textarea>
                                </div>
                            </div>--}}
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
                                    <input type="number" name="list_price" id="list_price" step=".01"
                                           class="form-control" autocomplete="off" placeholder="e.g. 10985.24"
                                           value="{{ old('list_price') ?? $order_details->list_price }}"/>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="metallic_paint" class="col-md-2 col-form-label">Metallic Paint (£)</label>
                                <div class="col-md-6">
                                    <input type="number" name="metallic_paint" id="metallic_paint" step=".01"
                                           class="form-control" autocomplete="off" placeholder="e.g. 389.55"
                                           value="{{ old('metallic_paint') ?? $order_details->metallic_paint }}"/>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="dealer_discount" class="col-md-2 col-form-label">Dealer Discount (%)</label>
                                <div class="col-md-6">
                                    <input type="number" name="dealer_discount" id="dealer_discount" step=".01"
                                           class="form-control discount" autocomplete="off" placeholder="e.g. 2.857"
                                           value="{{ old('dealer_discount') ?? $order_details->dealer_discount }}"/>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="manufacturer_discount" class="col-md-2 col-form-label">Manufacturer Discount
                                    (%)</label>
                                <div class="col-md-6">
                                    <input type="number" name="manufacturer_discount" id="manufacturer_discount"
                                           step=".01" class="form-control discount" autocomplete="off"
                                           placeholder="e.g. 3.879"
                                           value="{{ old('manufacturer_discount') ?? $order_details->manufacturer_discount }}"/>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="total_discount" class="col-md-2 col-form-label">Total Discount (%)</label>
                                <div class="col-md-6">
                                    <input type="number" name="total_discount" id="total_discount" class="form-control"
                                           placeholder="Dealer + Manufacturer"
                                           value="{{ $order_details->dealer_discount + $order_details->manufacturer_discount }}"
                                           disabled/>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="manufacturer_delivery_cost" class="col-md-2 col-form-label">Manufacturer
                                    Delivery Cost (£)</label>
                                <div class="col-md-6">
                                    <input type="number" name="manufacturer_delivery_cost"
                                           id="manufacturer_delivery_cost" step=".01" class="form-control invoice-total"
                                           autocomplete="off" placeholder="e.g. 88.59"
                                           value="{{ old('manufacturer_delivery_cost') ?? $order_details->manufacturer_delivery_cost }}"/>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="first_reg_fee" class="col-md-2 col-form-label">1st Reg Fee (£)</label>
                                <div class="col-md-6">
                                    <input type="number" name="first_reg_fee" id="first_reg_fee" step=".01"
                                           class="form-control" autocomplete="off" placeholder="e.g. 199.99"
                                           value="{{ old('first_reg_fee') ?? $order_details->first_reg_fee }}"/>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="rfl_cost" class="col-md-2 col-form-label">RFL (£)</label>
                                <div class="col-md-6">
                                    <input type="number" name="rfl_cost" id="rfl_cost" step=".01" class="form-control"
                                           autocomplete="off" placeholder="e.g. 85.00"
                                           value="{{ old('rfl_cost') ?? $order_details->rfl_cost }}"/>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="onward_delivery" class="col-md-2 col-form-label">Onward Delivery (£)</label>
                                <div class="col-md-6">
                                    <input type="number" name="onward_delivery" id="onward_delivery" step=".01"
                                           class="form-control invoice-total" autocomplete="off" placeholder="e.g. 85.00"
                                           value="{{ old('onward_delivery') ?? $order_details->onward_delivery }}"/>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="invoice_funder_for" class="col-md-2 col-form-label">Invoice Funder For
                                    (£)</label>
                                <div class="col-md-6">
                                    <input type="number" name="invoice_funder_for" id="invoice_funder_for" step=".01"
                                           class="form-control" autocomplete="off" placeholder="e.g. 85.00"
                                           value="{{ old('invoice_funder_for') ?? $order_details->invoice_funder_for }}"/>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="invoice_value" class="col-md-2 col-form-label">Invoice Value (£)</label>
                                <div class="col-md-6">
                                    <input type="text" name="invoice_value" id="invoice_value" class="form-control" disabled
                                           value="{{ number_format($order_details->invoiceValue(), 2, '.', '') }}"/>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="show_discount" class="col-md-2 col-form-label">Show Discount Applied</label>
                                <div class="col-md-6">
                                    <select name="show_discount" id="show_discount" class="form-control">
                                        <option value="0" @if ($order_details->show_discount == 0) selected @endif>No
                                        </option>
                                        <option value="1" @if ($order_details->show_discount == 1) selected @endif>Yes
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="show_offer" class="col-md-2 col-form-label">Show as Offer</label>
                                <div class="col-md-6">
                                    <select name="show_offer" id="show_offer" class="form-control">
                                        <option value="0" @if ($order_details->show_offer == 0) selected @endif>No
                                        </option>
                                        <option value="1" @if ($order_details->show_offer == 1) selected @endif>Yes
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="hide_from_broker" class="col-md-2 col-form-label">Hide From Broker
                                    List</label>
                                <div class="col-md-6">
                                    <select name="hide_from_broker" id="hide_from_broker" class="form-control">
                                        <option value="0" @if ($order_details->hide_from_broker == 0) selected @endif>
                                            No
                                        </option>
                                        <option value="1" @if ($order_details->hide_from_broker == 1) selected @endif>
                                            Yes
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="hide_from_dealer" class="col-md-2 col-form-label">Hide From Dealer
                                    List</label>
                                <div class="col-md-6">
                                    <select name="hide_from_dealer" id="hide_from_dealer" class="form-control">
                                        <option value="0" @if ($order_details->hide_from_dealer == 0) selected @endif>
                                            No
                                        </option>
                                        <option value="1" @if ($order_details->hide_from_dealer == 1) selected @endif>
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
                                <label for="invoice_finance" class="col-md-2 col-form-label">Commission to Finance
                                    Company (£)</label>
                                <div class="col-md-6">
                                    <input type="number" name="invoice_finance" id="invoice_finance" step=".01"
                                           class="form-control" autocomplete="off" placeholder="e.g. 134.25"
                                           value="{{ old('invoice_finance') ?? $order_details->invoice_finance }}"/>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="invoice_finance_number" class="col-md-2 col-form-label">Commission to
                                    Finance Company Invoice Number</label>
                                <div class="col-md-6">
                                    <input type="text" name="invoice_finance_number" id="invoice_finance_number"
                                           class="form-control" autocomplete="off" placeholder="e.g. CF1234"
                                           value="{{ old('invoice_finance_number') ?? $order_details->invoice_finance_number }}"/>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-2 col-form-label" for="finance_commission_paid">Finance Commission
                                    Pay Date</label>
                                <div class="col-md-6">
                                    <input type="text" name="finance_commission_paid" id="finance_commission_paid"
                                           class="form-control" autocomplete="off" placeholder="e.g. 30/03/2019"
                                           value="{{ old('finance_commission_paid') ?? $order_details->finance_commission_paid }}"/>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="invoice_broker" class="col-md-2 col-form-label">Invoice to Broker
                                    (£)</label>
                                <div class="col-md-6">
                                    <input type="number" name="invoice_broker" id="invoice_broker" step=".01"
                                           class="form-control" autocomplete="off" placeholder="e.g. 85.71"
                                           value="{{ old('invoice_broker') ?? $order_details->invoice_broker }}"/>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="invoice_broker_number" class="col-md-2 col-form-label">Invoice to Broker
                                    Invoice Number</label>
                                <div class="col-md-6">
                                    <input type="text" name="invoice_broker_number" id="invoice_broker_number"
                                           class="form-control" autocomplete="off" placeholder="e.g. BO568"
                                           value="{{ old('invoice_broker_number') ?? $order_details->invoice_broker_number }}"/>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-2 col-form-label" for="invoice_broker_paid">Broker Invoice Pay
                                    Date</label>
                                <div class="col-md-6">
                                    <input type="text" name="invoice_broker_paid" id="invoice_broker_paid"
                                           class="form-control" autocomplete="off" placeholder="e.g. 30/03/2019"
                                           value="{{ old('invoice_broker_paid') ?? $order_details->invoice_broker_paid }}"/>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="commission_broker" class="col-md-2 col-form-label">Commission to Broker
                                    (£)</label>
                                <div class="col-md-6">
                                    <input type="number" name="commission_broker" id="commission_broker" step=".01"
                                           class="form-control" autocomplete="off" placeholder="e.g. 420.81"
                                           value="{{ old('commission_broker') ?? $order_details->commission_broker }}"/>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="commission_broker_number" class="col-md-2 col-form-label">Commission to
                                    Broker Invoice Number</label>
                                <div class="col-md-6">
                                    <input type="text" name="commission_broker_number" id="commission_broker_number"
                                           class="form-control" autocomplete="off" placeholder="e.g. CB4097"
                                           value="{{ old('commission_broker_number') ?? $order_details->commission_broker_number }}"/>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-2 col-form-label" for="commission_broker_paid">Broker Commission
                                    Pay Date</label>
                                <div class="col-md-6">
                                    <input type="text" name="commission_broker_paid" id="commission_broker_paid"
                                           class="form-control" autocomplete="off" placeholder="e.g. 30/03/2019"
                                           value="{{ old('commission_broker_paid') ?? $order_details->commission_broker_paid }}"/>
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
                                <label for="customer_phone" class="col-md-2 col-form-label">Customer Phone
                                    Number</label>
                                <div class="col-md-6">
                                    <input type="text" name="customer_phone" id="customer_phone"
                                           class="form-control" autocomplete="off" placeholder="e.g. 07900 000 000"
                                           value="{{ old('customer_phone') ?? $order_details->customer_phone }}"/>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="delivery_address_1" class="col-md-2 col-form-label">Address Line 1</label>
                                <div class="col-md-6">
                                    <input type="text" name="delivery_address_1" id="delivery_address_1"
                                           class="form-control" autocomplete="off" placeholder="e.g. 20 Saturn Road"
                                           value="{{ old('delivery_address_1') ?? $order_details->delivery_address_1 }}"/>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="delivery_address_2" class="col-md-2 col-form-label">Address Line 2</label>
                                <div class="col-md-6">
                                    <input type="text" name="delivery_address_2" id="delivery_address_2"
                                           class="form-control" autocomplete="off" placeholder="Optional"
                                           value="{{ old('delivery_address_2') ?? $order_details->delivery_address_2 }}"/>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="delivery_town" class="col-md-2 col-form-label">Town</label>
                                <div class="col-md-6">
                                    <input type="text" name="delivery_town" id="delivery_town" class="form-control"
                                           autocomplete="off" placeholder="e.g. Blisworth"
                                           value="{{ old('delivery_town') ?? $order_details->delivery_town }}"/>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="delivery_city" class="col-md-2 col-form-label">City</label>
                                <div class="col-md-6">
                                    <input type="text" name="delivery_city" id="delivery_city" class="form-control"
                                           autocomplete="off" placeholder="e.g. Northampton"
                                           value="{{ old('delivery_city') ?? $order_details->delivery_city }}"/>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="delivery_county" class="col-md-2 col-form-label">County</label>
                                <div class="col-md-6">
                                    <input type="text" name="delivery_county" id="delivery_county" class="form-control"
                                           autocomplete="off" placeholder="e.g. Northamptonshire"
                                           value="{{ old('delivery_county') ?? $order_details->delivery_county }}"/>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="delivery_postcode" class="col-md-2 col-form-label">Postcode</label>
                                <div class="col-md-6">
                                    <input type="text" name="delivery_postcode" id="delivery_postcode"
                                           class="form-control" autocomplete="off" placeholder="e.g. NN7 3DB"
                                           value="{{ old('delivery_postcode') ?? $order_details->delivery_postcode }}"/>
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
                                    <textarea name="comments" id="comments" class="form-control" rows="4"
                                              value="{{ old('comments') ?? $order_details->comments }}"></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-md-2 col-form-label" for="file">Upload Document<br>
                                    <small>Allowed file types - JPEG, PNG, PDF, DOC & DOCX</small>
                                </label>
                                <div class="col-md-6">
                                    <input type="file"
                                           accept=".pdf, applicaion/pdf, image/png, .png, image/jpg, .jpg, image/jpeg, .jpeg, .doc, .docx, application/msword"
                                           name="file" id="file"/>
                                </div>
                            </div>
                            @if (count($attachments) > 0)
                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label">Attachments</label>
                                    <div class="col-md-8">
                                        <div id="attachment_message"></div>
                                        @foreach ($attachments as $file)
                                            <div class="btn-group" id="attachment{{$file->id}}">
                                                @if ($file->file_type == 'pdf')
                                                    <a class="btn btn-sm btn-info"
                                                       href="{{ env('APP_URL') }}/user-uploads/order-attachments/{{ $file->file_name }}"
                                                       download><i class="far fa-file-pdf"></i> {{ $file->file_name }}
                                                    </a>
                                                @elseif ($file->file_type == 'doc' || $file->file_type == 'docx')
                                                    <a class="btn btn-sm btn-info"
                                                       href="{{ env('APP_URL') }}/user-uploads/order-attachments/{{ $file->file_name }}"
                                                       download><i class="far fa-file-word"></i> {{ $file->file_name }}
                                                    </a>
                                                @elseif ($file->file_type == 'png' || $file->file_type == 'jpg' || $file->file_type == 'jpeg')
                                                    <a class="btn btn-sm btn-info"
                                                       href="{{ env('APP_URL') }}/user-uploads/order-attachments/{{ $file->file_name }}"
                                                       download><i class="far fa-file-image"></i> {{ $file->file_name }}
                                                    </a>
                                                @else
                                                    <a class="btn btn-sm btn-info"
                                                       href="{{ env('APP_URL') }}/user-uploads/order-attachments/{{ $file->file_name }}"
                                                       download><i class="far fa-file"></i> {{ $file->file_name }}</a>
                                                @endif
                                                <a class="btn btn-sm btn-danger btn-attachment-delete"
                                                   data-upload-id="{{$file->id}}"
                                                   href="{{route('order.attachment.delete', $file->id)}}"><i
                                                            class="far fa-trash-alt"></i></a>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                        <!-- Card Footer -->
                        <div class="card-footer text-right">
                            <button class="btn btn-primary" type="submit">Save Order</button>
                            <a href="{{ route('order.show', $order_details->id) }}" class="btn btn-secondary">View Order</a>
                        </div>
                    </div>
                    {{--</div>--}}
                </form>

            </div>

        </div>
        <!-- /.container-fluid -->

        @endsection

        @push('custom-scripts')
            <script>
                //debugger;
                $('.discount').change(function () {

                    let value = 0
                    $('.discount').each(function () {
                        if ($(this).val()) {
                            value = value + parseFloat($(this).val())
                        }
                    })
                    $('#total_discount').val(parseFloat(value).toFixed(2));
                })

                $('.invoice-total, #list_price, #metallic_paint, #dealer_discount, #manufacturer_discount, #first_reg_fee, #rfl_cost').change(function () {
                    let value = 0;

                    value += parseFloat($('#list_price').val());
                    value += parseFloat($('#metallic_paint').val());

                    let dealerDiscount = (value / 100) * parseFloat($('#dealer_discount').val());
                    let manufacturerSupport = (value / 100) * parseFloat($('#manufacturer_discount').val());

                    value -= dealerDiscount;
                    value -= manufacturerSupport;

                    $('.invoice-total').each(function () {
                        console.log(this.tagName);

                        let itemTotal = 0;
                        let discount = 0;
                        switch (this.tagName){
                            case 'SELECT':
                                $(this).find(':selected').each(function() {
                                    itemTotal += parseFloat($(this).data('cost'));
                                });
                                break;
                            case 'INPUT':
                                itemTotal += parseFloat($(this).val());
                                break;
                        }

                        if(this.name == 'factory_option[]'){
                            discount =  (itemTotal / 100) * (
                                parseFloat($('#dealer_discount').val()) +
                                parseFloat($('#manufacturer_discount').val())
                            );
                        }

                        value += itemTotal;
                        value -= discount;
                    })

                    let vat = (value / 100) * 20;
                    let invoice_value = value + vat + parseFloat($('#first_reg_fee').val()) + parseFloat($('#rfl_cost').val());


                    $('#invoice_value').val(invoice_value.toFixed(2));
                })

                $(function () {
                    $('.btn-attachment-delete').click(function (e) {
                        e.preventDefault();
                        let uploadId = $(this).data('upload-id');
                        console.log(uploadId);

                        let link = $(this).prop('href');
                        let message = $('#attachment_message');

                        $.get(link)
                            .done(function (r) {
                                $('#attachment' + uploadId).html(r.msg);
                            })
                            .fail(function () {
                                message.html('Failed to delete');
                            });
                    })
                })
            </script>
    @endpush
