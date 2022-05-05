<div>
    @livewire('fit-options.new-fit-option', ['fitType' => $fitType])
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
                <td>{{ $fitOption->model_year }}MY</td>
                @if($fitType === 'dealer')
                    <td>
                        @if($fitOption->dealer)
                            {{ $fitOption->dealer->company_name }}
                        @endif
                    </td>
                @endif
                <td>£{{ number_format($fitOption->option_price, 2, '.', '') }}</td>
                <td>@livewire('fit-options.edit-fit-option', ['fitOption' => $fitOption->id, 'key' => time().$fitOption->id ])</td>
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
