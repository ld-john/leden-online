@props([
    'label',
    'name',
    'value' => 0,
])

<div class="form-group row">
    <label for="{{ $name }}" class="col-md-2 col-form-label">
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
                    type="number"
                    step="0.01"
                    name="{{ $name }}"
                    id="{{ $name }}"
                    value="{{ $value }}"
                    class="form-control"
                    autocomplete="off"
                    readonly
                    {{ $attributes }}
            />
        </div>
    </div>
</div>
