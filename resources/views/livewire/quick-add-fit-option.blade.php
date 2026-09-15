<div>
    @if($showModal)
        <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5);" aria-modal="true" role="dialog">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content shadow-lg border-0">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title fs-6 fw-bold">
                            <i class="fa-solid fa-plus-circle me-1"></i> Add New {{ ucfirst($fitType) }} Fit Option
                        </h5>
                        <button type="button" class="btn-close btn-close-white" wire:click="$set('showModal', false)"></button>
                    </div>

                    <form wire:submit.prevent="save(false)">
                        <div class="modal-body py-3">
                            @if(session()->has('success_msg'))
                                <div class="alert alert-success alert-dismissible fade show small py-2 mb-3" role="alert">
                                    <i class="fa-solid fa-circle-check me-1"></i> {{ session('success_msg') }}
                                </div>
                            @endif

                            <div class="row g-3">
                                {{-- Target Model --}}
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Vehicle Model <span class="text-danger">*</span></label>
                                    <select wire:model="vehicle_model_id" class="form-select @error('vehicle_model_id') is-invalid @enderror">
                                        <option value="">Select Model...</option>
                                        @foreach($vehicle_models as $m)
                                            <option value="{{ $m->id }}">{{ $m->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('vehicle_model_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                {{-- Model Year --}}
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Model Year <span class="text-danger">*</span></label>
                                    <input type="text" wire:model="model_year" class="form-control @error('model_year') is-invalid @enderror" placeholder="e.g. 2026.25" />
                                    @error('model_year') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                {{-- Dealer Selection (If Dealer Fit) --}}
                                @if($fitType === 'dealer')
                                    <div class="col-md-12">
                                        <label class="form-label fw-bold">Dealer <span class="text-danger">*</span></label>
                                        <select wire:model="dealer_id" class="form-select @error('dealer_id') is-invalid @enderror">
                                            <option value="">Select Dealer...</option>
                                            @foreach($dealers as $d)
                                                <option value="{{ $d->id }}">{{ $d->company_name }}</option>
                                            @endforeach
                                        </select>
                                        @error('dealer_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                @endif

                                {{-- Option Name --}}
                                <div class="col-md-8">
                                    <label class="form-label fw-bold">Option Name <span class="text-danger">*</span></label>
                                    <input type="text" wire:model="option_name" class="form-control @error('option_name') is-invalid @enderror" placeholder="e.g. Rear Parking Sensors" autofocus />
                                    @error('option_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                {{-- Option Price --}}
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Price (&pound;) <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">&pound;</span>
                                        <input type="text" wire:model="price" class="form-control @error('price') is-invalid @enderror" placeholder="250.00" />
                                    </div>
                                    @error('price') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer bg-light d-flex justify-content-between">
                            <button type="button" class="btn btn-secondary btn-sm" wire:click="$set('showModal', false)">Close</button>

                            <div class="d-flex gap-2">
                                <button type="button" wire:click="save(true)" class="btn btn-outline-primary btn-sm fw-bold">
                                    <i class="fa-solid fa-plus me-1"></i> Save & Add Another
                                </button>
                                <button type="submit" class="btn btn-primary btn-sm fw-bold">
                                    <i class="fa-solid fa-check me-1"></i> Save & Close
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
