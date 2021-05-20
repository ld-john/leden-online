<div class="form-group row">
    <div class="col-md-5">
        <select wire:model.lazy="dealer_fit_options" class="custom-select" multiple>
            @foreach ($dealer_options as $dealer_option)
                <option data-cost="{{ $dealer_option->option_price }}" value="{{ $dealer_option->id }}">
                    {{ $dealer_option->option_name }} - &pound;{{ $dealer_option->option_price }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-5">
        <div class="row dealer-row">
            <div class="col-md-6">
                <input wire:model="dealer_fit_name_manual_add" type="text" class="form-control" placeholder="e.g. LED Lights"/>
                @error('dealer_fit_name_manual_add') <div class="alert alert-danger">{!! $message !!} </div> @enderror
            </div>
            <div class="col-md-6">
                <input wire:model="dealer_fit_price_manual_add" type="number" step=".01" class="form-control"
                       placeholder="e.g. 20.99"/>
                @error('dealer_fit_price_manual_add') <div class="alert alert-danger">{!! $message !!} </div> @enderror
            </div>
        </div>
        <div class="add-dealer-con mt-4">
            <button wire:click="newDealerFit" class="btn btn-sm btn-secondary" id="add-dealer-option" type="button">
                Add New Option
            </button>
        </div>
    </div>
</div>