{{-- Customer Phone Number --}}
<div class="form-group row">
    <label for="customer_phone" class="col-md-2 col-form-label">Customer Phone Number</label>
    <div class="col-md-6">
        <input wire:model="customer_phone" type="text" name="customer_phone" id="customer_phone"
               class="form-control" autocomplete="off" placeholder="e.g. 07900 000 000"/>
    </div>
</div>
{{-- Address 1 --}}
<div class="form-group row">
    <label for="delivery_address_1" class="col-md-2 col-form-label">Address Line 1</label>
    <div class="col-md-6">
        <input wire:model="delivery_address_1" type="text" name="delivery_address_1" id="delivery_address_1"
               class="form-control" autocomplete="off" placeholder="e.g. 20 Saturn Road"/>
    </div>
</div>
{{-- Address 2 --}}
<div class="form-group row">
    <label for="delivery_address_2" class="col-md-2 col-form-label">Address Line 2</label>
    <div class="col-md-6">
        <input wire:model="delivery_address_2" type="text" name="delivery_address_2" id="delivery_address_2"
               class="form-control" autocomplete="off" placeholder="Optional"/>
    </div>
</div>
{{-- Town --}}
<div class="form-group row">
    <label for="delivery_town" class="col-md-2 col-form-label">Town</label>
    <div class="col-md-6">
        <input wire:model="delivery_town" type="text" name="delivery_town" id="delivery_town" class="form-control"
               autocomplete="off" placeholder="e.g. Blisworth"/>
    </div>
</div>
{{-- City --}}
<div class="form-group row">
    <label for="delivery_city" class="col-md-2 col-form-label">City</label>
    <div class="col-md-6">
        <input wire:model="delivery_city" type="text" name="delivery_city" id="delivery_city" class="form-control"
               autocomplete="off" placeholder="e.g. Northampton"/>
    </div>
</div>
{{-- County --}}
<div class="form-group row">
    <label for="delivery_county" class="col-md-2 col-form-label">County</label>
    <div class="col-md-6">
        <input wire:model="delivery_county" type="text" name="delivery_county" id="delivery_county" class="form-control"
               autocomplete="off" placeholder="e.g. Northamptonshire"/>
    </div>
</div>
{{-- Postcode --}}
<div class="form-group row">
    <label for="delivery_postcode" class="col-md-2 col-form-label">Postcode</label>
    <div class="col-md-6">
        <input wire:model="delivery_postcode" type="text" name="delivery_postcode" id="delivery_postcode"
               class="form-control" autocomplete="off" placeholder="e.g. NN7 3DB"/>
    </div>
</div>