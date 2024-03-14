{{-- Customer Desired Delivery Month --}}
<div class="form-group row">
    <label for="delivery_month" class="col-md-2 col-form-label">Desired Delivery Month</label>
    <div class="col-md-6">
        <select wire:model.live="delivery_month" id="delivery_month" class="form-control">
            <option value="">---</option>
            <option value="Jan">January</option>
            <option value="Feb">February</option>
            <option value="Mar">March</option>
            <option value="Apr">April</option>
            <option value="May">May</option>
            <option value="Jun">June</option>
            <option value="Jul">July</option>
            <option value="Aug">August</option>
            <option value="Sep">September</option>
            <option value="Oct">October</option>
            <option value="Nov">November</option>
            <option value="Dec">December</option>
        </select>
    </div>
</div>
{{-- Customer Phone Number --}}
<div class="form-group row">
    <label for="customer_phone" class="col-md-2 col-form-label">Customer Phone Number</label>
    <div class="col-md-6">
        <input wire:model.live="customer_phone" type="text" name="customer_phone" id="customer_phone"
               class="form-control" autocomplete="off" />
    </div>
</div>
{{-- Address 1 --}}
<div class="form-group row">
    <label for="delivery_address_1" class="col-md-2 col-form-label">Address Line 1</label>
    <div class="col-md-6">
        <input wire:model.live="delivery_address_1" type="text" name="delivery_address_1" id="delivery_address_1"
               class="form-control" autocomplete="off" />
    </div>
</div>
{{-- Address 2 --}}
<div class="form-group row">
    <label for="delivery_address_2" class="col-md-2 col-form-label">Address Line 2</label>
    <div class="col-md-6">
        <input wire:model.live="delivery_address_2" type="text" name="delivery_address_2" id="delivery_address_2"
               class="form-control" autocomplete="off" />
    </div>
</div>
{{-- Town --}}
<div class="form-group row">
    <label for="delivery_town" class="col-md-2 col-form-label">Town</label>
    <div class="col-md-6">
        <input wire:model.live="delivery_town" type="text" name="delivery_town" id="delivery_town" class="form-control"
               autocomplete="off" />
    </div>
</div>
{{-- City --}}
<div class="form-group row">
    <label for="delivery_city" class="col-md-2 col-form-label">City</label>
    <div class="col-md-6">
        <input wire:model.live="delivery_city" type="text" name="delivery_city" id="delivery_city" class="form-control"
               autocomplete="off" />
    </div>
</div>
{{-- County --}}
<div class="form-group row">
    <label for="delivery_county" class="col-md-2 col-form-label">County</label>
    <div class="col-md-6">
        <input wire:model.live="delivery_county" type="text" name="delivery_county" id="delivery_county" class="form-control"
               autocomplete="off" />
    </div>
</div>
{{-- Postcode --}}
<div class="form-group row">
    <label for="delivery_postcode" class="col-md-2 col-form-label">Postcode</label>
    <div class="col-md-6">
        <input wire:model.live="delivery_postcode" type="text" name="delivery_postcode" id="delivery_postcode"
               class="form-control" autocomplete="off" />
    </div>
</div>
