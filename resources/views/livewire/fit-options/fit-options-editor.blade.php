<div>
    <div class="container-xxl">
        <h3>Add New {{ ucfirst($fitType) }} Fit Option</h3>
        <div class="d-flex justify-content-between">
            <div class="form-group me-2 w-100">
                <label for="option_name">Option Name</label>
                <div class="input-group">
                    @error('option_name')
                    <div class="input-group-text">
                        <label class="input-group-text bg-danger text-white" for="option_name"><i class="fa fa-exclamation-triangle"></i></label>
                    </div>
                    @enderror
                    <input type="text" class="form-control" id="option_name" wire:model="option_name">
                </div>
            </div>
            <div class="form-group me-2 w-100">
                <label for="model">Model</label>
                <div class="input-group">
                    <select name="model" id="" class="form-control">
                        @foreach($vehicle_models as $vehicle_model)
                            <option value="{{ $vehicle_model->id }}">{{ $vehicle_model->name }}</option>
                        @endforeach
                    </select>
                </div>

            </div>
            <div class="form-group me-2 w-100">
                <label for="model_year">Model Year</label>
                <div class="input-group">
                    @error('model_year')
                    <div class="input-group-text">
                        <label class="input-group-text bg-danger text-white" for="model_year_input"><i class="fa fa-exclamation-triangle"></i></label>
                    </div>
                    @enderror
                    <input type="text" class="form-control model_year" id="model_year_input" wire:model="model_year" onchange="this.dispatchEvent(new InputEvent('input'))">
                </div>
            </div>
            @if($fitType === 'dealer')
                <div class="form-group me-2 w-100">
                    <label for="dealer">Dealer</label>
                    <select name="dealer" id="dealer" wire:model="dealer" class="form-control">
                        <option value=""></option>
                        @foreach($dealers as $option)
                            <option value="{{ $option->id }}">{{ $option->company_name }}</option>
                        @endforeach
                    </select>
                </div>
            @endif
            <div class="form-group me-2 w-100">
                <label for="price">Price</label>
                <div class="input-group">
                    @error('price')
                    <div class="input-group-text">
                        <label class="input-group-text bg-danger text-white" for="price"><i class="fa fa-exclamation-triangle"></i></label>
                    </div>
                    @enderror
                    <input type="text" class="form-control" id="price" wire:model="price">
                </div>

            </div>
            <button wire:click="addNewOption" type="button" class="btn btn-primary w-25">Add New Fit Option</button>
        </div>
    </div>
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
                <td>{{ $fitOption->vehicle_model->name }}</td>
                <td>{{ $fitOption->model_year }}MY</td>
                @if($fitType === 'dealer')
                    <td>
                        @if($fitOption->dealer)
                            {{ $fitOption->dealer->company_name }}
                        @endif
                    </td>
                @endif
                <td>£{{ number_format($fitOption->option_price, 2, '.', '') }}</td>
                <td>
                    @if(count($fitOption->vehicles) === 0)
                        <a data-toggle="tooltip" title="Delete Fit Option">
                            <livewire:fit-options.delete-fit-option :fitOption="$fitOption->id" :key="time().$fitOption->id" />
                        </a>
                    @endif
                    <livewire:fit-options.edit-fit-option :fitOption="$fitOption->id" :key="time().$fitOption->id" />
                </td>
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
