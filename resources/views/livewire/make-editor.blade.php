<div>
    @if (session()->has('message'))
        <h5 class="alert alert-success">{{ session('message') }}</h5>
    @endif
    <div class="row">
        <div class="col-6 mb-5">
            <h2>Add New Make </h2>
            <form wire:submit.prevent="newMake">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Make:</span>
                    </div>
                    <input
                            type="text"
                            class="form-control"
                            wire:model="newMakeName"

                    >
                    <div class="input-group-append">
                        <button
                                class="btn btn-primary"
                                type="submit"
                        >
                            <i class="fa fa-plus"></i> Add
                        </button>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-6 mb-5">
            <h2>Add New Model</h2>
            <form wire:submit.prevent="newModel">
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <label class="input-group-text" for="inputGroupSelect01">Make</label>
                    </div>
                    <select wire:model="newModelMake" class="custom-select" id="inputGroupSelect01">
                        <option selected>Choose...</option>
                        @foreach($makes as $make)
                            <option value="{{ $make->id }}">{{ $make->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Model:</span>
                    </div>
                    <input
                            type="text"
                            class="form-control"
                            wire:model="newModelName"

                    >
                    <div class="input-group-append">
                        <button
                                class="btn btn-primary"
                                type="submit"
                        >
                            <i class="fa fa-plus"></i> Add
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>
    <div class="row">
        <div class="col-6">
            <h2>Modify Existing Makes</h2>
            <p>Changes to the Make name WILL affect existing vehicles. Model Name will not.</p>


            <form wire:submit.prevent="save">
                <ul>
                    @foreach($makes as $make)
                        <livewire:make-name-editor :make="$make" :key="$loop->index" />
                    @endforeach
                </ul>
                <button type="submit" class="btn btn-success mb-3"><i class="fa fa-save"></i> Save all changes</button>
            </form>
        </div>

    </div>

</div>
