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
                <x-inputs.select label="Make" name="vehicleForm.make" :options="$this->manufacturers" add-url="{{ route('meta.make.index') }}" required />
                {{-- Model (Required) --}}
                <x-inputs.select label="Model" name="vehicleForm.model" :options="$this->vehicle_models" required />
                {{-- Orbit Number (Unique ID) --}}
                <x-inputs.text label="Orbit Number" name="vehicleForm.orbit_number" />
                {{-- Ford Order Number --}}
                <x-inputs.text label="Order Ref" name="vehicleForm.ford_order_number" />
                {{-- Type (Required) --}}
                <x-inputs.select label="Vehicle Type" name="vehicleForm.type" :options="$this->vehicleTypes" option-value="name" required />
                {{-- Reg Number --}}
                <x-inputs.text label="Registration Number" name="vehicleForm.reg" />
                {{-- Derivative (Required) --}}
                <x-inputs.select label="Derivative" name="vehicleForm.derivative" :options="$this->derivatives" option-value="name" add-url="{{ route('meta.derivative.index') }}" required />
                {{-- Engine (Required) --}}
                <x-inputs.select label="Engine" name="vehicleForm.engine" :options="$this->engines" option-value="name" add-url="{{ route('meta.engine.index') }}" required />
                {{-- Transmission (Required) --}}
                <x-inputs.select label="Transmission" name="vehicleForm.transmission" :options="$this->vehicleTransmission" option-value="name" add-url="{{ route('meta.transmission.index') }}" required />
                {{-- Fuel Type --}}
                <x-inputs.select label="Fuel Type" name="vehicleForm.fuel_type" :options="$this->fuel_types" option-value="name" add-url="{{ route('meta.fuel.index') }}" required />
                {{-- Colour --}}
                <x-inputs.select label="Colour" name="vehicleForm.colour" :options="$this->colours" option-value="name" add-url="{{ route('meta.colour.index') }}" required />
                {{-- Trim --}}
                <x-inputs.select label="Trim" name="vehicleForm.trim" :options="$this->trims" option-value="name" add-url="{{ route('meta.trim.index') }}" required />
                {{-- Dealership --}}
                <x-inputs.select label="Dealership" name="vehicleForm.dealer_id" placeholder="Select Dealership" :options="$this->dealers" option-label="company_name" required />
                {{-- Compound--}}
                <x-inputs.select label="Compound" name="vehicleForm.compound" :options="$this->compounds" option-value="name" add-url="{{ route('meta.compound.index') }}" />
                @if($vehicle)
                    @if($vehicle->ring_fenced_stock)
                        <x-inputs.select label="Select Broker" name="vehicleForm.broker_id" :options="$this->brokers" />
                    @endif
                @endif
                <x-inputs.text label="Chassis Prefix" name="vehicleForm.chassis_prefix" />
                <x-inputs.text label="Chassis" name="vehicleForm.chassis" />
                <x-inputs.select name="vehicleForm.vehicle_status" label="Vehicle Status" :options="$this->availableStatuses" required placeholder="Please Select Status" />
                {{-- Recall Reason --}}
                @if($vehicleForm->vehicle_status?->isRecalled())
                    <x-inputs.textarea name="vehicleForm.recall_reason" label="Describe the reason for the Recall" />
                @endif
                @if(Auth()->user()->role != 'broker')
                    <x-inputs.text label="Model Year" name="vehicleForm.model_year" onchange="this.dispatchEvent(new InputEvent('input'))" />
                @endif
                <x-inputs.date label="Vehicle Registered" name="vehicleForm.vehicle_registered_on" />
                <x-inputs.date label="Proposed Build Date" name="vehicleForm.build_date" />
                <x-inputs.date label="Due Date" name="vehicleForm.due_date" />
                <x-inputs.date label="Contract Date" name="vehicleForm.contract_date" />
                <div class="form-group row">
                    <label for="show_in_ford_pipeline" class="col-md-2 col-form-label">Show in Ford
                        Pipeline</label>
                    <div class="col-md-6">
                        <select wire:model.live="vehicleForm.ford_pipeline" name="show_in_ford_pipeline" id="show_in_ford_pipeline"
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
                <x-inputs.currency label="List Price" name="cost.list_price" />
                <x-inputs.currency label="Metallic Paint" name="cost.metallic_paint" />
                <x-inputs.currency label="1st Reg Fee" name="cost.first_reg_fee" />
                <x-inputs.currency label="RFL" name="cost.rfl_cost" />
                <x-inputs.currency label="Onward Delivery" name="cost.onward_delivery" />
                {{-- Available Offers --}}
                @if ($this->availableOffers->isNotEmpty())
                    <div class="mb-3 p-3 border rounded bg-light">
                        <label class="form-label d-block fw-bold">Available Tactical Offers</label>
                        @foreach($this->availableOffers as $offer)
                            <div class="form-check border-bottom py-2">
                                <input
                                        class="form-check-input"
                                        type="checkbox"
                                        wire:model.live="vehicleForm.selectedOffers"
                                        value="{{ $offer->id }}"
                                        id="offer-{{ $offer->id }}"
                                        wire:key="checkbox-{{ $offer->id }}"
                                >
                                <label class="form-check-label d-flex justify-content-between w-100" for="offer-{{ $offer->id }}">
                                    <span>{{ $offer->name }}</span>
                                    <span class="text-success fw-bold">- £{{ number_format($offer->value, 2) }}</span>
                                </label>
                            </div>
                        @endforeach

                        <div class="form-check py-2">
                            <input class="form-check-input"
                                   type="checkbox"
                                   value="none"
                                   wire:model.live="vehicleForm.selectedOffers"
                                   id="offer-none"
                                   wire:key="checkbox-none"
                            >
                            <label class="form-check-label text-muted" for="offer-none">
                                No offer applied
                            </label>
                        </div>
                    </div>
                @endif
                <x-inputs.boolean label="Show Discount Applied" name="cost.show_discount" />
                <x-inputs.boolean label="Show as Offer" name="cost.show_offer" />
                <x-inputs.boolean label="Hide from Broker List" name="cost.hide_from_broker" />
                <x-inputs.boolean label="Hide from Dealer List" name="cost.hide_from_dealer" />
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
