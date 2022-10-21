<div>
    <div class="container-xxl mb-4">
        <h2>Add New {{ ucfirst($metatype) }} </h2>
        <form wire:submit.prevent="new">
            <div class="input-group">
                <span class="input-group-text">{{ ucfirst($metatype) }} name:</span>

                <input
                        type="text"
                        class="form-control"
                        wire:model="new_name"

                >
                <button class="btn btn-primary"
                        type="submit"
                        wire:click.prevent="newVehicleMeta()"
                >
                    <i class="fa fa-plus"></i> Add
                </button>
            </div>
        </form>
    </div>

    <div class="col-12">

        <h2>Current {{ Str::plural(ucfirst($metatype)) }}</h2>

        <div>
            <table class="table table-bordered">
                <tr class="blue-background text-white">
                    <th>Name</th>
                    <th>Associated Models</th>
                    <th>Action</th>
                </tr>
                @foreach($metadata as $name)
                    <tr >
                        <td>
                            {{ $name->name }}
                        </td>
                        <td class="p-0">
                            <ul class="list-group list-group-flush">
                                @forelse( $name->vehicle_model as $model)
                                    <li class="list-group-item flex-fill">{{$model->name}}</li>
                                @empty
                                    <li class="list-group-item flex-fill">No vehicle associated with this {{ ucfirst($metatype) }}</li>
                                @endforelse
                            </ul>
                        </td>
                        <td>
                            <div class="btn-group">
                                <a data-toggle="tooltip" title="Edit {{ ucfirst($metatype) }}" wire:click="showEditMetaModal({{ $name->id }})" class="btn btn-warning"><i class="fa-solid fa-pen-to-square"></i></a>
                                <a data-toggle="tooltip" title="Delete {{ ucfirst($metatype) }}" wire:click="delete({{ $name->id }})" class="btn btn-danger"><i class="fa-solid fa-trash"></i></a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>

    </div>
    <div class="modal @if($editModel) show @endif">

        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit {{ ucfirst($metatype) }} - {{ $edit_meta_name }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" wire:click="hideModal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <label for="meta_name" class="mb-0 mt-2">{{ ucfirst($metatype) }} Name</label>
                    <input wire:model="edit_meta_name" type="text" name="meta_name" id="meta_name" class="form-control"
                           autocomplete="off"
                    />
                    <label for="meta_models" class="mb-0 mt-2">Associated Models</label>
                    <div class="card p-2" style="max-height: 400px; overflow: scroll">
                        @foreach($models as $model)
                            <div class="form-check">
                                <input wire:model="edit_meta_models" type="checkbox" class="form-check-input" value="{{ $model->id }}" id="modelCheck{{$loop->index}}">
                                <label for="modelCheck{{$loop->index}}" class="form-check-label">{{ $model->name }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" wire:click="hideModal">Close</button>
                    <button type="button" class="btn btn-primary" wire:click="saveMeta({{ $edit_meta_id }})">Save</button>
                </div>
            </div>
        </div>
    </div>
</div>
