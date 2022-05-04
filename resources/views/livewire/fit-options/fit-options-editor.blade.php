<div>
    <div class="d-flex justify-content-between align-items-center">
        <div class="w-25 p-3 d-flex align-items-center">
            Show
            <select wire:model="paginate" name="" id="" class="form-control mx-2">
                <option value='10'>10</option>
                <option value='25'>25</option>
                <option value='50'>50</option>
                <option value='100'>100</option>
            </select>
            entries
        </div>
    </div>
    <table class="table table-bordered">
        <thead>
        <tr class="blue-background text-white">
            <th>Option Name</th>
            <th>Model</th>
            <th>Model Year</th>
            @if($fitType === 'dealer')
                <th>Dealer</th>
            @endif
            <th>Price</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @forelse($fitOptions as $fitOption)
            <tr>
                <td>{{ $fitOption->option_name }}</td>
                <td>{{ $fitOption->model }}</td>
                <td>{{ $fitOption->model_year }}</td>
                @if($fitType === 'dealer')
                    <td>{{ $fitOption->dealer }}</td>
                @endif
                <td>£{{ number_format($fitOption->option_price, 2, '.', '') }}</td>
                <td>{{ $fitOption->option_type }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="6">No Matching Results found</td>
            </tr>
        @endforelse
        </tbody>
    </table>
    <div class="d-flex justify-content-between">
        @if(!$fitOptions->isEmpty())
            <p>Showing {{ $fitOptions->firstItem() }} - {{ $fitOptions->lastItem() }} of {{$fitOptions->total()}}</p>
        @endif
        <div>
            {{ $fitOptions->links() }}
        </div>
    </div>
</div>
