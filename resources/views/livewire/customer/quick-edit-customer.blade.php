<div class="d-inline-block">
    <button
            type="button"
            class="btn btn-warning"
            wire:click="toggleEditModal"
    >
        <i class="fa-solid fa-pencil"></i>
    </button>
    <div class="modal @if($modalShow) show @endif">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Quick Edit Customer Record - {{ $customer->id }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" wire:click="toggleEditModal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="mb-0 mt-2">Customer Name</p>
                    <input wire:model="customer_name" class="form-control" type="text">
                    <p class="mb-0 mt-2">Address Line 1</p>
                    <input wire:model="address_1" class="form-control" type="text">
                    <p class="mb-0 mt-2">Address Line 2</p>
                    <input wire:model="address_2" class="form-control" type="text">
                    <p class="mb-0 mt-2">Town</p>
                    <input wire:model="town" class="form-control" type="text">
                    <p class="mb-0 mt-2">City</p>
                    <input wire:model="city" class="form-control" type="text">
                    <p class="mb-0 mt-2">County</p>
                    <input wire:model="county" class="form-control" type="text">
                    <p class="mb-0 mt-2">Postcode</p>
                    <input wire:model="postcode" class="form-control" type="text">
                    <p class="mb-0 mt-2">Phone Number</p>
                    <input wire:model="phone_number" class="form-control" type="text">

                @if($errors->count())
                    <div class="alert alert-danger alert-dismissible fade show m-5">
                        <h4 class="alert-heading"><i class="fa fa-exclamation-triangle"></i> There are some issues.</h4>
                        <hr>
                        <ul>
                            {!! implode($errors->all('<li>:message</li>')) !!}
                        </ul>
                    </div>
                @endif
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" wire:click="toggleEditModal">Close</button>
                    <button type="button" class="btn btn-primary" wire:click="saveCustomer">Save</button>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
