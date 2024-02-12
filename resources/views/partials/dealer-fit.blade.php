<div class="form-group row">
    <div class="col-md-4">
        <div><strong>Model:</strong></div>
        <input type="text" disabled
               value="@if($model){{ \App\Models\VehicleModel::where('id', $model)->first()?->name }} @endif"
               class="form-control">
    </div>
    <div class="col-md-4">
        <div><strong>Dealer:</strong></div>
        <input type="text" disabled
               value=" @if($dealership) {{ \App\Models\Company::where('id', $dealership )->first()?->company_name }} @endif"
               class="form-control">
    </div>
    <div class="col-md-4">
        <div><strong>Search</strong></div>
        <input type="text" class="form-control" wire:model.live="dealerFitSearch">
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
                <td><input type="checkbox" value="{{$option->id}}" wire:model.live="dealerFitOptions"></td>
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
            <p>Showing {{ $dealer_options->firstItem() }} - {{ $dealer_options->lastItem() }}
                of {{$dealer_options->total()}}</p>
        @endif
        {{ $dealer_options->links('pagination.dealer-order-form-pagination') }}
    </div>
@else
    Please select the Model and Dealership above
@endif

@if($dealerFitOptionsArray)
    <div class="form-group row" style="margin-top: 10px">
        <table class="table table-bordered">
            <thead>
            <tr class="blue-background text-white">
                <th>Option Name</th>
            </tr>
            </thead>
            <tbody>

            @foreach($dealerFitOptionsArray as $option)
                <tr>
                    <td>{{ $option->option_name }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endif