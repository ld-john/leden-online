<label class="col-md-2 col-form-label" for="customer_name">Customer Name</label>
<input type="text" wire:model.live="customer_name" name="customer_name" id="customer_name" class="form-control">

<table class="table table-bordered mt-4">
    <thead>
    <tr class="blue-background text-white">
        <th style="width: 45px"></th>
        <th>Customer Name</th>
        <th>Address</th>
    </tr>
    </thead>
    <tbody>
    @forelse ( $customers as $customer )
        <tr>
            <td><input type="radio" value="{{$customer->id}}" wire:model.live="customer_id"></td>
            <td>{{ $customer->name() }}</td>
            <td>{{ $customer->address_1 }}</td>
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
    {{ $customers->links('pagination.customer-order-form-pagination') }}
</div>
