<div>
    <div class="input-group mb-2">
        <div class="input-group-prepend">
            <button class="btn btn-outline-secondary" type="button" wire:click.prevent="saveMakeModal()"><i class="fa-solid fa-floppy-disk"></i></button>
        </div>
        <input type="text" class="form-control" wire:model="makeName">
        @if(!$make->vehicles()->exists())
            <div class="input-group-append">
                <button class="btn btn-outline-danger" type="button" wire:click="deleteMake()" ><i class="fa-solid fa-trash"></i></button>
            </div>
        @endif
    </div>
    @if($make->models)
        <div class="ml-4">
        @foreach(json_decode($make->models) as $model)
            <livewire:edit-model-name :model="$model" :make="$make" :loop="$loop->index" :wire:key="$loop->index"/>
        @endforeach
        </div>
    @endif
    <div class="modal @if($editModalShow) show @endif">

        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Make - {{ $make->name }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" wire:click="toggleEditModal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to change the name of {{ $make->name }} to {{ $makeName }}? This WILL affect existing orders and vehicles.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" wire:click="toggleEditModal">No</button>
                    <button type="button" class="btn btn-danger" wire:click="saveMake()">Yes</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal @if($deleteModalShow) show @endif">

        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Delete Make - {{ $make->name }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" wire:click="toggleDeleteModal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete {{ $make->name }}?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" wire:click="toggleDeleteModal">No</button>
                    <button type="button" class="btn btn-danger" wire:click="destroyMake()">Yes</button>
                </div>
            </div>
        </div>
    </div>
</div>
