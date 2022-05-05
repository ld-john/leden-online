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
                    <h5 class="modal-title" id="editModalLabel">Edit Fit Option - {{ $fitOption->option_name }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" wire:click="toggleEditModal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="mb-0 mt-2">Option Name</p>
                    <input wire:model="option_name" type="text" name="option_name" id="option_name" class="form-control"
                           autocomplete="off"
                    />
                    <p class="mb-0 mt-2">Model</p>
                    <input wire:model="model" type="text" name="model" id="model" class="form-control"
                           autocomplete="off"
                    />
                    <p class="mb-0 mt-2">Model Year</p>
                    <input wire:model="model_year" type="text" name="model_year" class="form-control"
                           autocomplete="off"
                    />
                    @if($fitOption->option_type === 'dealer')
                        <p class="mb-0 mt-2">Dealer</p>
                        <select name="dealer" id="dealer" wire:model="dealer" class="form-control">
                            <option value=""></option>
                            @foreach($dealers as $option)
                                <option value="{{ $option->id }}">{{ $option->company_name }}</option>
                            @endforeach
                        </select>
                    @endif
                    <p class="mb-0 mt-2">Price</p>
                    <input wire:model="price" type="text" name="price" id="price" class="form-control"
                           autocomplete="off"
                    />
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" wire:click="toggleEditModal">Close</button>
                    <button type="button" class="btn btn-primary" wire:click="saveFitOption">Save</button>
                </div>
            </div>
        </div>
    </div>
</div>
