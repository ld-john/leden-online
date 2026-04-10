<div>
    <form wire:submit="orderFormSubmit" method="POST" enctype="multipart/form-data">
        <div class="card shadow mb-4">
            @csrf
            @if ($successMsg)
                <div class="alert alert-success" role="alert">
                    {{$successMsg}}
                </div>
            @endif
            <!-- Vehicle Information -->
            <x-ui.section-header>Car Information</x-ui.section-header>
            <div class="card-body">
                {{-- Make (Required) --}}
                <x-inputs.select label="Make" name="make" :options="$this->manufacturers" add-url="{{ route('meta.make.index') }}" required />
                {{-- Model (Required) --}}
                <x-inputs.select label="Model" name="model" :options="$this->vehicle_models" required />
                {{-- Orbit Number (Unique ID) --}}
                <x-inputs.text label="Orbit Number" name="orbit_number" />
                {{-- Ford Order Number --}}
                <x-inputs.text label="Order Ref" name="order_ref" />
                {{-- Type (Required) --}}
                <x-inputs.select label="Vehicle Type" name="type" :options="$this->vehicleTypes" option-value="name" required />
                {{-- Reg Number --}}
                <x-inputs.text label="Registration Number" name="registration" />
                {{-- Derivative (Required) --}}
                <x-inputs.select label="Derivative" name="derivative" :options="$this->derivatives" option-value="name" add-url="{{ route('meta.derivative.index') }}" required />
                {{-- Engine (Required) --}}
                <x-inputs.select label="Engine" name="engine" :options="$this->engines" option-value="name" add-url="{{ route('meta.engine.index') }}" required />
                {{-- Transmission (Required) --}}
                <x-inputs.select label="Transmission" name="transmission" :options="$this->vehicleTransmission" option-value="name" add-url="{{ route('meta.transmission.index') }}" required />
                {{-- Fuel Type --}}
                <x-inputs.select label="Fuel Type" name="fuel_type" :options="$this->fuel_types" option-value="name" add-url="{{ route('meta.fuel.index') }}" required />
                {{-- Colour --}}
                <x-inputs.select label="Colour" name="colour" :options="$this->colours" option-value="name" add-url="{{ route('meta.colour.index') }}" required />
                {{-- Trim --}}
                <x-inputs.select label="Trim" name="trim" :options="$this->trims" option-value="name" add-url="{{ route('meta.trim.index') }}" required />
                {{-- Dealership --}}
                <x-inputs.select label="Dealership" name="dealership" placeholder="Select Dealership" :options="$this->dealers" option-label="company_name" required />
                {{-- Compound--}}
                <x-inputs.select label="Compound" name="compound" :options="$this->compounds" option-value="name" add-url="{{ route('meta.compound.index') }}" />
                @if($vehicle)
                    @if($vehicle->ring_fenced_stock)
                        <x-inputs.select label="Select Broker" name="broker" :options="$this->brokers" />
                    @endif
                @endif
                <x-inputs.text label="Chassis Prefix" name="chassis_prefix" />
                <x-inputs.text label="Chassis" name="chassis" />
                <div class="form-group row">
                    <label class="col-md-2 col-form-label" for="vehicle_status"><i class="fa fa-asterisk fa-fw text-danger" aria-hidden="true"></i> Vehicle Status</label>
                    <div class="col-md-6">
                        <div class="input-group">
                            @error('status')
                            <label class="input-group-text bg-danger text-white" for="inputGroupSelectTrim"><i class="fa fa-exclamation-triangle"></i></label>
                            @enderror
                            <select wire:model.live="status" class="form-control" name="vehicle_status" id="vehicle_status">
                                <option value="">Please Select Status</option>
                                @foreach($this->availableStatuses as $status)
                                    <option value="{{ $status->value }}">
                                        {{ $status->label() }}
                                    </option>

                                @endforeach
                            </select>
                        </div>

                    </div>
                </div>
                @if(Auth()->user()->role != 'broker')
                    <x-inputs.text label="Model Year" name="model_year" onchange="this.dispatchEvent(new InputEvent('input'))" />
                @endif
                <x-inputs.date label="Vehicle Registered" name="vehicle_registered_on" />
                <x-inputs.date label="Proposed Build Date" name="build_date" />
                <x-inputs.date label="Due Date" name="due_date" />
                <x-inputs.date label="Contract Date" name="contract_date" />
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
            </div>
            <!-- Card Header -->
            <x-ui.section-header id="factory-fit">Factory Fit Options</x-ui.section-header>
            <!-- Card Body -->
            <div class="card-body">
                @include('partials.factory-fit')
            </div>
            <!-- Card Header -->
            <x-ui.section-header id="dealer-fit">Dealer Fit Options</x-ui.section-header>
            <!-- Card Body -->
            <div class="card-body">
                @include('partials.dealer-fit')
            </div>
            <!-- Card Header -->
            <x-ui.section-header>Cost Breakdown</x-ui.section-header>
            <!-- Card Body -->
            <div class="card-body">
                <x-inputs.currency label="List Price" name="list_price" />
                <x-inputs.currency label="Metallic Paint" name="metallic_paint" />
                <x-inputs.currency label="1st Reg Fee" name="first_reg_fee" />
                <x-inputs.currency label="RFL" name="rfl_cost" />
                <x-inputs.currency label="Onward Delivery" name="onward_delivery" />
                <x-inputs.currency label="Tactical Offers" name="tactical_offer" />
                <x-inputs.boolean label="Show Discount Applied" name="show_discount" />
                <x-inputs.boolean label="Show as Offer" name="show_offer" />
                <x-inputs.boolean label="Hide from Broker List" name="hide_from_broker" />
                <x-inputs.boolean label="Hide from Dealer List" name="hide_from_dealer" />
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
