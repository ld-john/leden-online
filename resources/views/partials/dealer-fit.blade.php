<div class="form-group row">
    <div class="col-md-4">
        <div><strong>Model:</strong></div>
        <input type="text" disabled wire:model="model" class="form-control">
    </div>
    <div class="col-md-4">
        <div><strong>Dealer:</strong></div>
        <input type="text" disabled value=" @if($dealership) {{ \App\Company::where('id', $dealership )->first()?->company_name }} @endif" class="form-control">
    </div>
    <div class="col-md-4">
        <div><strong>Search</strong></div>
        <input type="text" class="form-control" wire:model="dealerFitSearch">
    </div>
</div>
@if($model && $dealership)
    <table class="table table-bordered">
        <thead>
        <tr class="blue-background text-white">
            <th></th>
            <th>Option Name</th>
            <th>Price</th>
        </tr>
        </thead>
        <tbody>
        @forelse($dealer_options as $option)
            <tr>
                <td><input type="checkbox" value="{{$option->id}}" wire:model="dealer_fit_options"></td>
                <td>{{$option->option_name}}</td>
                <td>£{{ number_format($option->option_price, 2, '.', '') }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="3">Sorry, Nothing matches your search</td>
            </tr>
        @endforelse
    </table>
    <div class="d-flex justify-content-between">
        @if(!$dealer_options->isEmpty())
            <p>Showing {{ $dealer_options->firstItem() }} - {{ $dealer_options->lastItem() }} of {{$dealer_options->total()}}</p>
        @endif
        <div>
            {{ $dealer_options->links() }}
        </div>
    </div>
@else
    Please select the Model and Dealership above
@endif
