<div class="mb-5">
    <button type="button" class="btn btn-primary" wire:click.debounce.300ms="$set('newCustomer', true)">New Customer</button>
    <button type="button" class="btn btn-primary" wire:click.debounce.300ms="$set('newCustomer', false)">Existing Customer</button>
</div>

<!-- Group the Customer Creation -->

@if($newCustomer)

    <div class="form-group row">
        <label class="col-md-2 col-form-label" for="customer_name">Customer Name</label>
        <div class="col-md-6">
            <input wire:model.lazy="name" type="text" name="customer_name" id="customer_name" class="form-control" placeholder="e.g. Ted Moseby" />
        </div>
    </div>

@else

    <label for="customer" class="col-md-2 col-form-label">Select Existing Customer</label>
    <div class="col-md-6">
        <select wire:model="customer_id" name="preferred_name" id="preferred_name" class="form-control customer_select" wire:model="preferred">
            <option></option>
            @foreach ( $customers as $customer )
                <option value="{{ $customer->id }}">
                    {{ $customer->customer_name }}
                    @if ( $customer->customer_name && $customer->company_name )/@endif
                    {{$customer->company_name}}
                </option>
            @endforeach
        </select>
    </div>


@endif
