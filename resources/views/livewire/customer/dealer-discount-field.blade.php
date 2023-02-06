<div>
    <div class="row form-group">
        <label class="col-md-2 col-form-label" for="company_discount">{{ $model->name }}</label>
        <div class="col-md-8">
            <input wire:model="discount" type="number" step="0.01" name="company_discount" id="company_discount" required class="form-control" autocomplete="off" value=""/>
        </div>
        <div class="col-md-2">
            <div class="btn-group">
                <div wire:click="saveDiscount" class="btn btn-success text-white">Save</div>
                <div wire:click="clearDiscount" class="btn btn-warning">Clear</div>
            </div>
        </div>

    </div>
</div>
