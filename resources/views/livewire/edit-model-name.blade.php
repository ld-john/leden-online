<div>
    <div class="input-group mb-2 ml-2">
        <div class="input-group-prepend">
            <button class="btn btn-outline-secondary" type="button" wire:click="saveModel()"><i class="fa-solid fa-floppy-disk"></i></button>
        </div>
        <input type="text" wire:model="model" class="form-control ">
        <div class="input-group-append">
            <button class="btn btn-outline-danger" type="button" wire:click="deleteModel()"><i class="fa-solid fa-trash"></i></button>
        </div>
    </div>
</div>
