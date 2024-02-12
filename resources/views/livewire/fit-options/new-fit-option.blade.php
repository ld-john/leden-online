<div>
    <h3>Add New {{ ucfirst($fitType) }} Fit Option</h3>
    <div class="d-flex just">
        <div class="form-group mr-2">
            <label for="option_name">Option Name</label>
            <div class="input-group">
                @error('option_name')
                <div class="input-group-prepend">
                    <label class="input-group-text bg-danger text-white" for="option_name"><i class="fa fa-exclamation-triangle"></i></label>
                </div>
                @enderror
                <input type="text" class="form-control" id="option_name" wire:model.live="option_name">
            </div>
        </div>
        <div class="form-group mr-2">
            <label for="model">Model</label>
            <div class="input-group">
                @error('model')
                <div class="input-group-prepend">
                    <label class="input-group-text bg-danger text-white" for="model"><i class="fa fa-exclamation-triangle"></i></label>
                </div>
                @enderror
                <input type="text" class="form-control" id="model" wire:model.live="model">
            </div>

        </div>
        <div class="form-group mr-2">
            <label for="model_year">Model Year</label>
            <div class="input-group">
                @error('model_year')
                <div class="input-group-prepend">
                    <label class="input-group-text bg-danger text-white" for="model_year_input"><i class="fa fa-exclamation-triangle"></i></label>
                </div>
                @enderror
                <input type="text" class="form-control model_year" id="model_year_input" wire:model.live="model_year" onchange="this.dispatchEvent(new InputEvent('input'))">
            </div>
        </div>
        @if($fitType === 'dealer')
            <div class="form-group mr-2">
                <label for="dealer">Dealer</label>
                <select name="dealer" id="dealer" wire:model.live="dealer" class="form-control">
                    <option value=""></option>
                    @foreach($dealers as $option)
                        <option value="{{ $option->id }}">{{ $option->company_name }}</option>
                    @endforeach
                </select>
            </div>
        @endif
        <div class="form-group mr-2">
            <label for="price">Price</label>
            <div class="input-group">
                @error('price')
                <div class="input-group-prepend">
                    <label class="input-group-text bg-danger text-white" for="price"><i class="fa fa-exclamation-triangle"></i></label>
                </div>
                @enderror
                <input type="text" class="form-control" id="price" wire:model.live="price">
            </div>

        </div>
        <button wire:click="addNewOption" type="button" class="btn btn-primary">Add New Fit Option</button>
    </div>
</div>
