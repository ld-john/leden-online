<div class="d-inline-block">
    <button class="btn btn-warning" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasCustomer{{ $customer->id }}" aria-controls="offcanvasExample">
        <i class="fa-solid fa-pencil"></i>
    </button>

    <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasCustomer{{ $customer->id }}" aria-labelledby="offcanvasExampleLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="offcanvasExampleLabel">Quick Edit Customer Record - {{ $customer->id }}</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <p class="mb-0 mt-2">Customer Name</p>
            <input wire:model.live="customer_name" class="form-control" type="text">
            <p class="mb-0 mt-2">Address Line 1</p>
            <input wire:model.live="address_1" class="form-control" type="text">
            <p class="mb-0 mt-2">Address Line 2</p>
            <input wire:model.live="address_2" class="form-control" type="text">
            <p class="mb-0 mt-2">Town</p>
            <input wire:model.live="town" class="form-control" type="text">
            <p class="mb-0 mt-2">City</p>
            <input wire:model.live="city" class="form-control" type="text">
            <p class="mb-0 mt-2">County</p>
            <input wire:model.live="county" class="form-control" type="text">
            <p class="mb-0 mt-2">Postcode</p>
            <input wire:model.live="postcode" class="form-control" type="text">
            <p class="mb-0 mt-2">Phone Number</p>
            <input wire:model.live="phone_number" class="form-control" type="text">

            @if($errors->count())
                <div class="alert alert-danger alert-dismissible fade show m-5">
                    <h4 class="alert-heading"><i class="fa fa-exclamation-triangle"></i> There are some issues.</h4>
                    <hr>
                    <ul>
                        {!! implode($errors->all('<li>:message</li>')) !!}
                    </ul>
                </div>
            @endif
            <button type="button" class="btn btn-primary mt-4" wire:click="saveCustomer">Save</button>
        </div>
    </div>
</div>
