@props([
    'label',
    'name',
    'required' => false,
])

<div class="form-group row">
    <label for="{{ $name }}" class="col-md-2 col-form-label">
        @if($required) <i class="fa fa-asterisk fa-fw text-danger" aria-hidden="true"></i> @endif
        {{ $label }}
    </label>

    <div class="col-md-6">
        <div class="input-group">
            @error($name)
            <label class="input-group-text bg-danger text-white">
                <i class="fa fa-exclamation-triangle"></i>
            </label>
            @enderror

            {{-- Decorative Currency Symbol --}}
            <span class="input-group-text">£</span>

            <input
                    wire:model.blur="{{ $name }}"
                    type="number"
                    step="0.01"
                    name="{{ $name }}"
                    id="{{ $name }}"
                    class="form-control"
                    autocomplete="off"
                    {{ $attributes }}
            />
        </div>
    </div>
</div>
