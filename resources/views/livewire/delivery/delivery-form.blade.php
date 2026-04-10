<div>
    <form>
        @csrf
        @if (session()->has('message'))
            <div class="alert alert-success" role="alert">
                {{session('message')}}
                <br>
                <div class="btn-group">
                    <a href="{{ route('order.pdf', $order->id) }}" class="btn btn-secondary">Download Order PDF</a>
                    <a href="{{ route('order.show', $order->id) }}" class="btn btn-primary">View Order</a>
                </div>
            </div>
        @endif
        @if($errors->count())
            <div class="alert alert-danger alert-dismissible fade show m-5">
                <h4 class="alert-heading"><i class="fa fa-exclamation-triangle"></i> There are some issues.</h4>
                <hr>
                <ul>
                    {!! implode($errors->all('<li>:message</li>')) !!}
                </ul>
            </div>
        @endif
        <div class="card mb-3">
            <x-ui.section-header>Delivery Information</x-ui.section-header>
            <div class="card-body">
                {{-- Ford Order Number --}}
                <x-inputs.text label="Ford Order Number" name="order_ref" disabled />

                {{-- Customer Name --}}
                <x-inputs.text label="Customer Name" name="customer_name" disabled />

                {{-- Registration Number --}}
                <x-inputs.text label="Registration Number" name="reg" disabled />

                {{-- Chassis --}}
                <x-inputs.text label="Chassis" name="chassis" disabled />

                {{-- Funder --}}
                <x-inputs.text label="Funder" name="funder" disabled />

                {{-- Partner --}}
                <x-inputs.text label="Partner" name="partner" disabled />

                {{-- Delivery Date --}}
                <x-inputs.date label="Delivery Date" name="delivery_date" />

                {{-- Delivery Address 1 --}}
                <x-inputs.text label="Delivery Address 1" name="delivery_address1" />

                {{-- Delivery Address 2 --}}
                <x-inputs.text label="Delivery Address 2" name="delivery_address2" />

                {{-- Delivery Town --}}
                <x-inputs.text label="Delivery Town" name="delivery_town" />

                {{-- Delivery City --}}
                <x-inputs.text label="Delivery City" name="delivery_city" />

                {{-- Delivery Postcode --}}
                <x-inputs.text label="Delivery Postcode" name="delivery_postcode" />

                {{-- Contact Name --}}
                <x-inputs.text label="Contact Name" name="contact_name" />

                {{-- Contact Number --}}
                <x-inputs.text label="Contact Number" name="contact_number" />

                {{-- Delivery Agent --}}
                <x-inputs.select label="Delivery Agent" name="selectedDeliveryAgent" :options="$this->deliveryAgents" option-label="company_name" />

                {{-- Funder Confirmation --}}
                <div class="form-group row">
                    <label for="funder_confirmation" class="col-md-2 col-form-label">Funder Confirmation</label>
                    <div class="col-md-6">
                        @if($funder_confirmation)
                            Funder Confirmation Uploaded - <a class="btn btn-danger" wire:click="clearFunderConfirmation">Clear</a>
                        @else
                            <input wire:model.live="funder_confirmation"  type="file" name="funder_confirmation" id="funder_confirmation" />
                        @endif
                    </div>
                </div>

            </div>
            <div class="card-footer text-right">
                <a wire:click="cancelBooking" class="btn btn-danger">Cancel Booking</a>
                <a wire:click="requestBooking" class="btn btn-success">Save Booking</a>
            </div>
        </div>
    </form>
</div>
