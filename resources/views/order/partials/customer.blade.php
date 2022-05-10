<label class="col-md-2 col-form-label" for="customer_name">Customer Name</label>
<input type="text" wire:model="customer_name" name="customer_name" id="customer_name" class="form-control">

<table class="table table-bordered mt-4">
    <thead>
    <tr class="blue-background text-white">
        <th style="width: 45px"></th>
        <th>Customer Name</th>
    </tr>
    </thead>
    <tbody>
    @forelse ( $customers as $customer )
        <tr>
            <td><input type="radio" value="{{$customer->id}}" wire:model="customer_id"></td>
            <td>{{ $customer->customer_name }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="2">
                No Results - Set up a new Customer or search again
            </td>
        </tr>
    @endforelse
    </tbody>
</table>
@if ($customer_id)
    <button class="btn btn-danger" wire:click.prevent="clearCustomerID">Clear</button>
@endif
<div class="d-flex justify-content-between">
    @if(!$customers->isEmpty())
        <p>Showing {{ $customers->firstItem() }} - {{ $customers->lastItem() }} of {{$customers->total()}}</p>
    @endif
    <div>
        {{ $customers->links() }}
    </div>
</div>

<!-- Group the Customer Creation -->
{{--@if($newCustomer)--}}
{{--    <div class="form-group row">--}}
{{--        <label class="col-md-2 col-form-label" for="customer_name">Customer Name</label>--}}
{{--        <div class="col-md-6">--}}
{{--            <input wire:model="customer_name" type="text" name="customer_name" id="customer_name" class="form-control" />--}}
{{--        </div>--}}
{{--    </div>--}}
{{--@else--}}
{{--    <label for="customer" class="col-md-2 col-form-label">Select Existing Customer</label>--}}
{{--    <div class="col-md-6">--}}
{{--        <select wire:model="customer_id" name="preferred_name" id="preferred_name" class="form-control" wire:model="preferred">--}}
{{--            <option></option>--}}
{{--            @foreach ( $customers as $customer )--}}
{{--                <option value="{{ $customer->id }}">--}}
{{--                    {{ $customer->customer_name }}--}}
{{--                </option>--}}
{{--            @endforeach--}}
{{--        </select>--}}
{{--    </div>--}}
{{--@endif--}}
