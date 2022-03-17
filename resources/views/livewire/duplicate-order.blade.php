<div class="d-inline-block">
    <button
            type="button"
            class="btn btn-primary"
            wire:click="toggleDuplicateModal"
    >
        <i class="fas fa-copy"></i>
    </button>
    <div class="modal @if($modalShow) show @endif">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Duplicate Order - {{ $order->id }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" wire:click="toggleDuplicateModal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="copy">How many copies</label>
                        <input wire:model="duplicateQty" type="text" name="copy" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" wire:click="toggleDuplicateModal">Close</button>
                    <button type="button" class="btn btn-primary" wire:click="duplicateOrder">Duplicate</button>
                </div>
            </div>
        </div>
    </div>
</div>
