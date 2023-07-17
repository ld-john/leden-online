<div class="form-group row">
    <div class="col-md-4">
        <div><strong>Model:</strong></div>
        <input type="text" disabled
               value="@if($model){{ \App\Models\VehicleModel::where('id', $model)->first()?->name }} @endif"
               class="form-control">
    </div>
    <div class="col-md-4">
        <div><strong>Model Year:</strong></div>
        <input type="text" disabled wire:model="model_year" class="form-control">
    </div>
    <div class="col-md-4">
        <div><strong>Search</strong></div>
        <input type="text" class="form-control" wire:model="factoryFitSearch">
    </div>
</div>
@if($model && $model_year)
    <table class="table table-bordered">
        <thead>
        <tr class="blue-background text-white">
            <th></th>
            <th>Option Name</th>
            <th>Price</th>
        </tr>
        </thead>
        <tbody>
        @forelse($factory_options as $option)
            <tr>
                <td><input type="checkbox" value="{{$option->id}}" wire:model="factoryFitOptions"></td>
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
        @if(!$factory_options->isEmpty())
            <p>Showing {{ $factory_options->firstItem() }} - {{ $factory_options->lastItem() }}
                of {{$factory_options->total()}}</p>
        @endif

        {{ $factory_options->links('pagination.factory-order-form-pagination') }}

    </div>
@else
    Please select the Model and Model Year above
@endif
@if($factoryFitOptionsArray)
<div class="form-group row" style="margin-top: 10px">
    <table class="table table-bordered">
        <thead>
        <tr class="blue-background text-white">
            <th>Option Name</th>
        </tr>
        </thead>
        <tbody>

        @foreach($factoryFitOptionsArray as $option)
            <tr>
                <td>{{ $option->option_name }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

@endif