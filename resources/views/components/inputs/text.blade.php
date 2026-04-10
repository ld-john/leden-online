@props([
    'label',
    'name',
    'type' => 'text',
    'required' => false
])

<div class="form-group row">
    <label class="col-md-2 col-form-label" for="{{ $name }}">
        @if($required) <i class="fa fa-asterisk fa-fw text-danger" aria-hidden="true"></i> @endif
        {{ $label }}
    </label>

    <div class="col-md-6 mb-3">
        <div class="input-group">
            @error($name)
            <label class="input-group-text bg-danger text-white">
                <i class="fa fa-exclamation-triangle"></i>
            </label>
            @enderror

            <input
                    wire:model.blur="{{ $name }}"
                    type="{{ $type }}"
                    name="{{ $name }}"
                    id="{{ $name }}"
                    class="form-control"
                    {{ $attributes }}
            />
        </div>
    </div>
</div>
