<div>
    <div class="mb-4">
        <button wire:click="setType('update')"
                class="btn @if($type === 'update') btn-outline-primary disabled @else btn-primary @endif me-4">Update
        </button>
        <button wire:click="setType('promo')"
                class="btn @if($type === 'promo') btn-outline-primary disabled @else btn-primary @endif">Promotional
            Banner
        </button>
    </div>
    @if($type === 'update')
        <h4>New Update</h4>
        <div class="d-flex">
            <div class="w-100 me-2">
                <label for="text" class="form-label">Text</label>
                <input wire:model="text" maxlength="150" id="text" type="text" class="form-control">
                @error('text')
                <div class="alert alert-danger mt-2">{{ $errors->first('text')}}</div>
                @enderror
            </div>
            {{--        <div class="w-100 me-2">--}}
            {{--            <label for="target">For</label>--}}
            {{--            <select wire:model="target" class="form-control" name="target" id="target">--}}
            {{--                <option value="broker">Broker</option>--}}
            {{--            </select>--}}
            {{--        </div>--}}
            <button wire:click="saveUpdate" class="btn btn-primary">Save</button>
        </div>
    @endif
    @if($type === 'promo')
        <h4>New Promotional Banner</h4>
        <div class="d-flex">
            <div class="w-100 me-2">
                <label for="heading" class="form-label">Heading *</label>
                <input wire:model="heading" type="text" maxlength="50" id="heading" class="form-control">
                @error('heading')
                <div class="alert alert-danger mt-2">{{ $errors->first('heading')}}</div>
                @enderror
            </div>
            <div class="w-100 me-2">
                <label for="text" class="form-label">Text</label>
                <input wire:model="text" maxlength="150" id="text" type="text" class="form-control">
            </div>
            <div class="w-100 me-2">
                <label for="image" class="form-label">Image *</label><br>
                <input class="form-control" wire:model="image" id="image" type="file">
                <p class="small">Image must be 1600px x 500px</p>
                @error('image')
                <div class="alert alert-danger mt-2">{{ $errors->first('image')}}</div>
                @enderror
            </div>
            <button wire:click="saveBanner" class="btn btn-primary">Save</button>
        </div>
        <h4 class="my-4">Preview</h4>
        <div class="position-relative w-100">
            @if($heading && $image)
                <div class="position-absolute top-50 start-0 translate-middle-y ms-4 w-25 p-2 bg-white shadow">
                    <h3>{{ $heading }}</h3>
                    @if($text)
                        <p>{{ $text }}</p>
                    @endif
                </div>
                <img src="{{ $image->temporaryUrl() }}" alt="{{ $heading }}" class="w-100">
            @endif
        </div>

    @endif

    <h4 class="my-4">Active Updates</h4>
    <ul class="list-group">
        @forelse($updates as $update)
            <li class="list-group-item">{{$update->update_text}} <a
                        wire:click="deleteUpdate({{ $update->id }})" class="btn btn-danger text-white"><i
                            class="fa-solid fa-trash"></i></a></li>
        @empty
            <li class="list-group-item">No Updates Currently Active</li>
        @endforelse
    </ul>
    <h4 class="my-4">Active Banners</h4>
    <ul class="list-group mb-4">
        @forelse($banners as $banner)
            <li class="list-group-item position-relative">
                <div class="position-relative w-100">
                    <div class="position-absolute top-50 start-0 translate-middle-y ms-4 w-25 p-2 bg-white shadow">
                        <h3>{{ $banner->header }}</h3>
                        @if($banner->update_text)
                            <p>{{ $banner->update_text }}</p>
                        @endif
                    </div>
                    <img src="{{ asset($banner->image) }}" alt="{{ $banner->header }}" class="w-100">
                </div>
                <a wire:click="deleteUpdate({{ $banner->id }})" class="btn btn-danger text-white position-absolute top-0 end-0"><i class="fa-solid fa-trash"></i></a>
            </li>
        @empty
            <li class="list-group-item">No Banners Currently Active</li>
        @endforelse
    </ul>
    <div class="row my-4">
        @if(count($updates_bin) > 0)
            <div class="col">
                <h4>Recently Deleted Updates</h4>
                <ul class="list-group">
                    @foreach($updates_bin as $update)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>{{ $update->update_text }}</span>
                            <div class="d-flex flex-column">
                                <div class="badge bg-primary rounded-pill">
                                    {{ $update->deleted_at }}
                                </div>
                                <div class="btn-group mt-1">
                                    <a class="btn btn-warning" data-toggle="tooltip" title="Restore" wire:click="restoreUpdate({{ $update->id }})"><i class="fa-solid fa-trash-can-arrow-up"></i></a>
                                    <a class="btn btn-danger" data-toggle="tooltip" title="Permanently Delete" wire:click="destroyUpdate({{ $update->id }})"><i class="fa-solid fa-trash"></i></a>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if(count($banners_bin) > 0)
            <div class="col">
                <h4>Recently Deleted Banners</h4>
                <ul class="list-group">
                    @foreach($banners_bin as $banner)
                        <li class="list-group-item position-relative">
                            <div class="position-relative w-100">
                                <div class="position-absolute top-50 start-0 translate-middle-y ms-4 w-25 p-2 bg-white shadow">
                                    <h3>{{ $banner->header }}</h3>
                                    @if($banner->update_text)
                                        <p>{{ $banner->update_text }}</p>
                                    @endif
                                </div>
                                <img src="{{ asset($banner->image) }}" alt="{{ $banner->header }}" class="w-100">
                            </div>
                            <div class="btn-group position-absolute top-0 end-0">
                                <a class="btn btn-warning" data-toggle="tooltip" title="Restore" wire:click="restoreUpdate({{ $banner->id }})"><i class="fa-solid fa-trash-can-arrow-up"></i></a>
                                <a class="btn btn-danger" data-toggle="tooltip" title="Permanently Delete" wire:click="destroyUpdate({{ $banner->id }})"><i class="fa-solid fa-trash"></i></a>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

    </div>
</div>
