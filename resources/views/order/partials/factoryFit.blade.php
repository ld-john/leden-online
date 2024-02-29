<div class="form-group row">
    <div class="col-md-5">
        <select wire:model.blur="factory_fit_options" class="form-select" multiple>
            @foreach ($factory_options as $factory_option)

                <option data-cost="{{ $factory_option->option_price }}" value="{{ $factory_option->id }}" >{{ $factory_option->option_name }} -
                    &pound;{{ $factory_option->option_price }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-5 factory-row">
        <div class="row">
            <div class="col-md-6">
                <label>Name</label>
                <input wire:model.live="factory_fit_name_manual_add" type="text" class="form-control" />
                @error('factory_fit_name_manual_add') <div class="alert alert-danger">{!! $message !!} </div> @enderror
            </div>
            <div class="col-md-6">
                <label>Price</label>
                <input wire:model.live="factory_fit_price_manual_add" type="number" step=".01" class="form-control" />
                @error('factory_fit_price_manual_add') <div class="alert alert-danger">{!! $message !!} </div> @enderror
            </div>
        </div>
        <div class="add-factory-con mt-4">
            <button wire:click="newFactoryFit" class="btn btn-sm btn-secondary" type="button">
                Add New Option
            </button>
        </div>
    </div>
</div>
