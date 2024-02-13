<div>
    <div class="row g-3 form-group">
        <p class="lead text-center">{{ $model->name }}</p>
        <label class="col-md-2 col-form-label" for="company_discount">Vehicle Discount</label>
        <div class="col-md-4">
            <div class="input-group">
                <input wire:model.live="discount" type="number" step="0.01" name="company_discount" id="company_discount" required class="form-control" autocomplete="off" value=""/>
                <span class="input-group-text">%</span>
            </div>
        </div>


        <label class="col-md-2 col-form-label" for="company_paint_discount">Metallic Paint Discount</label>
        <div class="col-md-4">
            <div class="input-group">
                <input wire:model.live="paint_discount" type="number" step="0.01" name="company_paint_discount" id="company_paint_discount" required class="form-control" autocomplete="off" value=""/>
                <span class="input-group-text">%</span>
            </div>
        </div>
        <div class="col-md-4 btn-group mx-auto">
            <button wire:click="saveDiscount" class="btn btn-success text-white">Save</button>
            <button wire:click="clearDiscount" class="btn btn-danger text-white">Delete</button>
        </div>
    </div>
    <hr>
</div>
