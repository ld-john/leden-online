<div>
    {{-- Because she competes with no one, no one can compete with her. --}}
    <div>
        <h2>Add New {{ $metatype }} </h2>
        <form wire:submit.prevent="new">
            <label for="name">Name: </label>
            <input name="name" type="text" wire:model="newname">
            <button type="submit">New</button>
        </form>
    </div>

    <div class="table-responsive">
        <form wire:submit.prevent="save">
            <table class="table table-bordered" id="dataTable" width="50%" cellspacing="0">
                <thead>
                <tr>
                    <th>Name</th>
                    <th width="10px">Action</th>
                </tr>
                </thead>
                <tbody>

                @foreach($metadata as $index => $name)

                    <tr>

                        <td wire:key="meta-field-{{ $index }}">

                            <input type="text" wire:model.lazy="metadata.{{ $index }}.name"/>

                        </td>
                        <td>
                            <button wire:click.prevent="removeEntry({{ $name->id }}, '{{ $name->name }}')">Destroy</button>
                        </td>

                    </tr>
                @endforeach
                </tbody>
            </table>
            <button type="submit">Save all changes</button>
        </form>
    </div>



</div>
