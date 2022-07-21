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
            <div class="card-body">
                {{-- Ford Order Number --}}
                <div class="form-group row">
                    <label for="order_ref" class="col-md-2 col-form-label">Ford Order Number</label>
                    <div class="col-md-6">
                        <input wire:model="order_ref" type="text" name="order_ref" id="order_ref" class="form-control"
                               autocomplete="off" disabled />
                    </div>
                </div>
                {{-- Customer Name --}}
                <div class="form-group row">
                    <label for="customer_name" class="col-md-2 col-form-label">Customer Name</label>
                    <div class="col-md-6">
                        <input wire:model="customer_name" type="text" name="customer_name" id="customer_name" class="form-control"
                               autocomplete="off" disabled />
                    </div>
                </div>
                {{-- Registration Number --}}
                <div class="form-group row">
                    <label for="reg" class="col-md-2 col-form-label">Registration Number</label>
                    <div class="col-md-6">
                        <input wire:model="reg" type="text" name="reg" id="reg" class="form-control"
                               autocomplete="off" disabled />
                    </div>
                </div>
                {{-- Chassis --}}
                <div class="form-group row">
                    <label for="chassis" class="col-md-2 col-form-label">Chassis</label>
                    <div class="col-md-6">
                        <input wire:model="chassis" type="text" name="chassis" id="chassis" class="form-control"
                               autocomplete="off" disabled />
                    </div>
                </div>
                {{-- Funder --}}
                <div class="form-group row">
                    <label for="funder" class="col-md-2 col-form-label">Funder</label>
                    <div class="col-md-6">
                        <input wire:model="funder" type="text" name="funder" id="funder" class="form-control"
                               autocomplete="off" disabled />
                    </div>
                </div>
                {{-- Partner --}}
                <div class="form-group row">
                    <label for="partner" class="col-md-2 col-form-label">Partner</label>
                    <div class="col-md-6">
                        <input wire:model="partner" type="text" name="partner" id="partner" class="form-control"
                               autocomplete="off" disabled />
                    </div>
                </div>
                {{-- Delivery Date --}}
                <div class="form-group row">
                    <label for="delivery_date" class="col-md-2 col-form-label">Delivery Date</label>
                    <div class="col-md-6">
                        <input wire:model="delivery_date"  type="date" name="delivery_date" id="proposed_delivery_date" class="form-control" min="{{ $earliest_delivery_date }}"
                               autocomplete="off" />
                    </div>
                </div>
                {{-- Delivery Address 1 --}}
                <div class="form-group row">
                    <label for="delivery_address1" class="col-md-2 col-form-label">Delivery Address 1</label>
                    <div class="col-md-6">
                        <input wire:model="delivery_address1"  type="text" name="delivery_address1" id="delivery_address1" class="form-control"
                               autocomplete="off" />
                    </div>
                </div>
                {{-- Delivery Address 2 --}}
                <div class="form-group row">
                    <label for="delivery_address2" class="col-md-2 col-form-label">Delivery Address 2</label>
                    <div class="col-md-6">
                        <input wire:model="delivery_address2"  type="text" name="delivery_address2" id="delivery_address2" class="form-control"
                               autocomplete="off" />
                    </div>
                </div>
                {{-- Delivery Town --}}
                <div class="form-group row">
                    <label for="delivery_town" class="col-md-2 col-form-label">Delivery Town</label>
                    <div class="col-md-6">
                        <input wire:model="delivery_town"  type="text" name="delivery_town" id="delivery_town" class="form-control"
                               autocomplete="off" />
                    </div>
                </div>
                {{-- Delivery City --}}
                <div class="form-group row">
                    <label for="delivery_city" class="col-md-2 col-form-label">Delivery City</label>
                    <div class="col-md-6">
                        <input wire:model="delivery_city"  type="text" name="delivery_city" id="delivery_city" class="form-control"
                               autocomplete="off" />
                    </div>
                </div>
                {{-- Delivery Postcode --}}
                <div class="form-group row">
                    <label for="delivery_postcode" class="col-md-2 col-form-label">Delivery Postcode</label>
                    <div class="col-md-6">
                        <input wire:model="delivery_postcode"  type="text" name="delivery_postcode" id="delivery_postcode" class="form-control"
                               autocomplete="off" />
                    </div>
                </div>
                {{-- Contact Name --}}
                <div class="form-group row">
                    <label for="contact_name" class="col-md-2 col-form-label">Contact Name</label>
                    <div class="col-md-6">
                        <input wire:model="contact_name"  type="text" name="contact_name" id="contact_name" class="form-control"
                               autocomplete="off" />
                    </div>
                </div>
                {{-- Contact Number --}}
                <div class="form-group row">
                    <label for="contact_number" class="col-md-2 col-form-label">Contact Number</label>
                    <div class="col-md-6">
                        <input wire:model="contact_number"  type="text" name="contact_number" id="contact_number" class="form-control"
                               autocomplete="off" />
                    </div>
                </div>
                {{-- Funder Confirmation --}}
                <div class="form-group row">
                    <label for="funder_confirmation" class="col-md-2 col-form-label">Funder Confirmation</label>
                    <div class="col-md-6">
                        <input wire:model="funder_confirmation"  type="file" name="funder_confirmation" id="funder_confirmation" />
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
