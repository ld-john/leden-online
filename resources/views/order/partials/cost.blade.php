{{-- List Price --}}
<div class="form-group row">
    <label for="list_price" class="col-md-2 col-form-label">List Price</label>
    <div class="col-md-6">
        <div class="input-group">
            <div class="input-group-text">
                £
            </div>
            <input wire:model="list_price" type="number" name="list_price" id="list_price" step=".01" class="form-control" autocomplete="off" onchange="invoiceValueChange()"/>
        </div>
    </div>
</div>
{{-- Metallic Paint --}}
<div class="form-group row">
    <label for="metallic_paint" class="col-md-2 col-form-label">Metallic Paint</label>
    <div class="col-md-6">
        <div class="input-group">
            <div class="input-group-text">
                £
            </div>
            <input wire:model="metallic_paint" type="number" name="metallic_paint" id="metallic_paint" step=".01" class="form-control" autocomplete="off" onchange="invoiceValueChange()" />
        </div>
    </div>
</div>
{{-- Dealer Discount --}}
<div class="form-group row">
    <label for="dealer_discount" class="col-md-2 col-form-label">Dealer Discount</label>
    <div class="col-md-6">
        <div class="input-group">
            <div class="input-group-text">
                <span class="form-check-label me-2">Override?</span>
                <input type="checkbox" class="form-check" aria-label="Checkbox for Invoice Value Override" wire:model="dealer_discount_override">
            </div>
            <input
                    wire:model="dealer_discount"
                    type="number"
                    name="dealer_discount"
                    id="dealer_discount"
                    step=".01"
                    class="form-control discount"
                    autocomplete="off"
                    onchange="invoiceValueChange()"
                    @if(!$dealer_discount_override) disabled @endif
            />
            <div class="input-group-text">
                %
            </div>
        </div>
    </div>
</div>
{{-- Manufacturer Discount --}}
<div class="form-group row">
    <label for="manufacturer_discount" class="col-md-2 col-form-label">Manufacturer Discount</label>
    <div class="col-md-6">
        <div class="input-group">
            <input
                    wire:model="manufacturer_discount"
                    type="number"
                    name="manufacturer_discount"
                    id="manufacturer_discount"
                    step=".01"
                    class="form-control discount"
                    autocomplete="off"
                    onchange="invoiceValueChange()"
            />
            <div class="input-group-text">
                %
            </div>
        </div>
    </div>
</div>
{{-- Total Discount --}}
<div class="form-group row">
    <label for="total_discount" class="col-md-2 col-form-label">Total Discount</label>
    <div class="col-md-6">
        <div class="input-group">
            <input
                    type="number"
                    name="total_discount"
                    id="total_discount"
                    class="form-control"
                    step="0.01"
                    placeholder="Dealer + Manufacturer"
                    disabled
            />
            <div class="input-group-text">
                %
            </div>
        </div>
    </div>
</div>
{{-- Manufacturer Delivery Cost --}}
<div class="form-group row">
    <label for="manufacturer_delivery_cost" class="col-md-2 col-form-label">Manufacturer Delivery Cost</label>
    <div class="col-md-6">
        <div class="input-group">
            <div class="input-group-text">
                £
            </div>
            <input
                    wire:model="manufacturer_delivery_cost"
                    type="number"
                    name="manufacturer_delivery_cost"
                    id="manufacturer_delivery_cost"
                    step=".01"
                    class="form-control invoice-total"
                    autocomplete="off"
                    onchange="invoiceValueChange()"
            />
        </div>
    </div>
</div>
{{-- 1st Reg Fee --}}
<div class="form-group row">
    <label for="first_reg_fee" class="col-md-2 col-form-label">1st Reg Fee</label>
    <div class="col-md-6">
        <div class="input-group">
            <div class="input-group-text">
                £
            </div>
            <input
                    wire:model="first_reg_fee"
                    type="number"
                    name="first_reg_fee"
                    id="first_reg_fee"
                    step=".01"
                    class="form-control"
                    autocomplete="off"
                    onchange="invoiceValueChange()"
            />
        </div>
    </div>
</div>
{{-- RFL --}}
<div class="form-group row">
    <label for="rfl_cost" class="col-md-2 col-form-label">RFL</label>
    <div class="col-md-6">
        <div class="input-group">
            <div class="input-group-text">
                £
            </div>
            <input
                    wire:model="rfl_cost"
                    type="number"
                    name="rfl_cost"
                    id="rfl_cost"
                    step=".01"
                    class="form-control"
                    autocomplete="off"
                    onchange="invoiceValueChange()"
            />
        </div>
    </div>
</div>
{{-- Onward Delivery --}}
<div class="form-group row">
    <label for="onward_delivery" class="col-md-2 col-form-label">Onward Delivery</label>
    <div class="col-md-6">
        <div class="input-group">
            <div class="input-group-text">
                £
            </div>
            <input
                    wire:model="onward_delivery"
                    type="number"
                    name="onward_delivery"
                    id="onward_delivery"
                    step=".01"
                    class="form-control invoice-total"
                    autocomplete="off"
                    onchange="invoiceValueChange()"
            />
        </div>
    </div>
</div>
{{-- Invoice Funder For --}}
<div class="form-group row">
    <label for="invoice_funder_for" class="col-md-2 col-form-label">Invoice Funder For</label>
    <div class="col-md-6">
        <div class="input-group">
            <div class="input-group-text">
                £
            </div>
            <input
                    wire:model="invoice_funder_for"
                    type="number"
                    name="invoice_funder_for"
                    id="invoice_funder_for"
                    step=".01"
                    class="form-control"
                    autocomplete="off"
                    onchange="invoiceValueChange()"
            />
        </div>
    </div>
</div>
{{-- Estimated Invoice Value --}}
<div class="form-group row">
    <label for="invoice_value" class="col-md-2 col-form-label">Estimated Invoice Value</label>
    <div class="col-md-6">
        <div class="input-group">
            <div class="input-group-text">
                £
            </div>
            <input type="text" name="invoice_value" id="invoice_value" class="form-control" disabled/>
        </div>
    </div>
</div>
{{-- Show Discount Applied --}}
<div class="form-group row">
    <label for="show_discount" class="col-md-2 col-form-label">Show Discount Applied</label>
    <div class="col-md-6">
        <select wire:model="show_discount" name="show_discount" id="show_discount" class="form-control">
            <option value="0">No
            </option>
            <option value="1">Yes
            </option>
        </select>
    </div>
</div>
{{-- Show as Offer --}}
<div class="form-group row">
    <label for="show_offer" class="col-md-2 col-form-label">Show as Offer</label>
    <div class="col-md-6">
        <select wire:model="show_offer" name="show_offer" id="show_offer" class="form-control">
            <option value="0">No
            </option>
            <option value="1">Yes
            </option>
        </select>
    </div>
</div>
{{-- Hide From Broker --}}
<div class="form-group row">
    <label for="hide_from_broker" class="col-md-2 col-form-label">Hide From Broker List</label>
    <div class="col-md-6">
        <select wire:model="hide_from_broker" name="hide_from_broker" id="hide_from_broker" class="form-control">
            <option value="0">
                No
            </option>
            <option value="1">
                Yes
            </option>
        </select>
    </div>
</div>
{{-- Hide From Dealer --}}
<div class="form-group row">
    <label for="hide_from_dealer" class="col-md-2 col-form-label">Hide From Dealer List</label>
    <div class="col-md-6">
        <select wire:model="hide_from_dealer" name="hide_from_dealer" id="hide_from_dealer" class="form-control">
            <option value="0">
                No
            </option>
            <option value="1">
                Yes
            </option>
        </select>
    </div>
</div>
