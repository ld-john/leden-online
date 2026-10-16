<div class="d-inline-block">
    <button class="btn btn-warning" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasCustomer{{ $customer->id }}" aria-controls="offcanvasExample">
        <i class="fa-solid fa-pencil"></i>
    </button>

    <div wire:ignore.self class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasCustomer{{ $customer->id }}" aria-labelledby="offcanvasExampleLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="offcanvasExampleLabel">Quick Edit Customer Record - {{ $customer->id }}</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <p class="mb-0 mt-2">Customer Name</p>
            <input wire:model.live="customerForm.name" class="form-control" type="text">
            <p class="mb-0 mt-2">Address Line 1</p>
            <input wire:model.live="customerForm.address_1" class="form-control" type="text">
            <p class="mb-0 mt-2">Address Line 2</p>
            <input wire:model.live="customerForm.address_2" class="form-control" type="text">
            <p class="mb-0 mt-2">Town</p>
            <input wire:model.live="customerForm.town" class="form-control" type="text">
            <p class="mb-0 mt-2">City</p>
            <input wire:model.live="customerForm.city" class="form-control" type="text">
            <p class="mb-0 mt-2">County</p>
            <input wire:model.live="customerForm.county" class="form-control" type="text">
            <p class="mb-0 mt-2">Postcode</p>
            <input wire:model.live="customerForm.postcode" class="form-control" type="text">
            <p class="mb-0 mt-2">Phone Number</p>
            <input wire:model.live="customerForm.phone_number" class="form-control" type="text">

            @if($errors->count())
                <div class="alert alert-danger alert-dismissible fade show m-5">
                    <h4 class="alert-heading"><i class="fa fa-exclamation-triangle"></i> There are some issues.</h4>
                    <hr>
                    <ul>
                        {!! implode($errors->all('<li>:message</li>')) !!}
                    </ul>
                </div>
            @endif
            <button type="button" class="btn btn-primary mt-4" wire:click="saveCustomer(true)">Save</button>
        </div>
    </div>
</div>
