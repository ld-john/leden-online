<div>
    <div class="input-group mb-2 ms-2">
        <button class="btn btn-outline-secondary" type="button" wire:click="saveModel()"><i class="fa-solid fa-floppy-disk"></i></button>
        <input type="text" wire:model="modelName" class="form-control ">
        <button class="btn btn-outline-danger" type="button" wire:click="deleteModel()"><i class="fa-solid fa-trash"></i></button>
    </div>
</div>
