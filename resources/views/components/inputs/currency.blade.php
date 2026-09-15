@props([
    'label',
    'name',
    'required' => false,
    'suffix' => null,
    'overrideName' => null,
    'isOverridden' => false
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
                    @disabled(($overrideName && !$isOverridden))
            />
            @if($suffix)
                <span class="input-group-text">{{ $suffix }}</span>
            @endif

            @if($overrideName)
                <div class="input-group-text">
                    <input
                            class="form-check-input mt-0"
                            type="checkbox"
                            wire:model.live="{{ $overrideName }}"
                            id="{{ $overrideName }}"
                            title="Override calculated value"
                    >
                    <label class="form-check-label ms-2 small" for="{{ $overrideName }}">Override</label>
                </div>
            @endif
        </div>
    </div>
</div>
