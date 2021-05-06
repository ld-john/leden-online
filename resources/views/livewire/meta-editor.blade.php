<div>
    {{-- Because she competes with no one, no one can compete with her. --}}
    <div class="col-5 mb-5">
        <h2>Add New {{ $metatype }} </h2>
        <form wire:submit.prevent="new">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text">{{ $metatype }} name:</span>
                </div>
                <input
                        type="text"
                        class="form-control"
                        wire:model="newname"

                >
                <div class="input-group-append">
                    <button
                            class="btn btn-primary"
                            type="button"
                            type="submit"
                            wire:click.prevent="new()"
                    >
                        <i class="fa fa-plus"></i> Add
                    </button>
                </div>
            </div>
        </form>


    </div>

    <div class="col-5">

    <h2>Modify {{ $metatype }} Fields</h2>
    <p>This will not affect current Vehicles</p>


        <form wire:submit.prevent="save">

                @foreach($metadata as $index => $name)


                            <div class="input-group mb-2">

                                <input
                                    type="text"
                                    class="form-control"
                                    wire:model.lazy="metadata.{{ $index }}.name"
                                >

                                <div class="input-group-append">
                                    <button
                                            class="btn btn-outline-danger"
                                            type="button"
                                            wire:click.prevent="removeEntry({{ $name->id }}, '{{ $name->name }}')"
                                    >
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </div>
                            </div>

                     @endforeach

            <button type="submit" class="btn btn-success mb-3"><i class="fa fa-save"></i> Save all changes</button>
        </form>
    </div>



</div>
