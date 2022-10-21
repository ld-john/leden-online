<div>
    <form class="mb-4" wire:submit.prevent="new">
        <div class="input-group">
            <span class="input-group-text">Finance type name:</span>

            <input
                    type="text"
                    class="form-control"
                    wire:model="new_name"

            >
            <button class="btn btn-primary"
                    type="submit"
                    wire:click.prevent="newFinanceType()"
            >
                <i class="fa fa-plus"></i> Add
            </button>
        </div>
    </form>
    <h2>Current Finance Types</h2>
    <div>
        <table class="table table-bordered">
            <tr class="blue-background text-white">
                <th>Option</th>
                <th style="width: 120px">Actions</th>
            </tr>
            @foreach($metadata as $item)
                <tr>
                    <td>{{ $item->option }}</td>
                    <td>
                        @if(!count($item->orders))
                            <div class="btn-group">
                                <a
                                        data-toggle="tooltip" title="Edit Finance Type"
                                        wire:click="showEditModal({{ $item->id }})"
                                        class="btn btn-warning"
                                        data-bs-toggle="offcanvas"
                                        data-bs-target="#offcanvas-edit-finance-type"
                                        aria-controls="offcanvasExample"
                                >
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <a data-toggle="tooltip" title="Delete Finance Type" wire:click="delete({{ $item->id }})" class="btn btn-danger"><i class="fa-solid fa-trash"></i></a>
                            </div>
                        @endif
                    </td>
                </tr>
            @endforeach
        </table>
        <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvas-edit-finance-type" aria-labelledby="offcanvasExampleLabel" wire:ignore.self>
            <div class="offcanvas-header">
                @if($edit)
                    <h5 class="offcanvas-title" id="offcanvasExampleLabel">Edit Finance Type - {{ $edit->option }}</h5>
                @endif
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <label for="edit_name">New Name</label>
                <input
                        id="edit_name"
                        type="text"
                        class="form-control"
                        wire:model="edit_name"

                >
                <div class="my-4">
                    <button type="button" class="btn btn-primary" wire:click="saveFinanceType">Save</button>
                </div>
            </div>
        </div>
    </div>
</div>
