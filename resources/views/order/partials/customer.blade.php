<label class="col-md-2 col-form-label" for="customer_name">Customer Name</label>
<input
        type="text" wire:model.live.debounce.300ms="customer.name"
        name="customer_name"
        id="customer_name"
        class="form-control"
        placeholder="Type at least 2 characters to search"
>

<table class="table table-bordered mt-4">
    <thead>
    <tr class="blue-background text-white">
        <th style="width: 45px"></th>
        <th>Customer Name</th>
        <th>Address</th>
    </tr>
    </thead>
    <tbody>
    @forelse ( $customers as $c )
        <tr>
            <td><input type="radio" value="{{$c->id}}" wire:model.live="customer.id"></td>
            <td>{{ $c->name() }}</td>
            <td>{{ $c->address_1 }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="2">
                No Results — Set up a new customer or adjust your search term.
            </td>
        </tr>
    @endforelse
    </tbody>
</table>

<div class="d-flex justify-content-between">
    @if(!$customers->isEmpty())
        <p>Showing {{ $customers->firstItem() }} - {{ $customers->lastItem() }} of {{$customers->total()}}</p>
    @endif
    {{ $customers->links() }}
</div>
