<div>
    <button
            type="button"
            class="btn btn-danger delete-order"
            wire:click="toggleDeleteModal"
    >
        <i class="fas fa-trash"></i>
    </button>
    <div class="modal @if($modalShow) show @endif">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Delete Customer Record - {{ $customer->id }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" wire:click="toggleDeleteModal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete the record for {{ $customer->customer_name }}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" wire:click="toggleDeleteModal">Close</button>
                    <button type="button" class="btn btn-danger" wire:click="deleteCustomer">Delete</button>
                </div>
            </div>
        </div>
    </div>
</div>
