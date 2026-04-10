@props([
    'label',
    'name',
    'required' => false,
    'readonly' => false,
    'value' => null,
    'overrideName' => null,
    'isOverridden' => false
])

<div class="form-group row mb-3">
    <label for="{{ $name }}" class="col-md-2 col-form-label">
        @if ($required) <i class="fa fa-asterisk fa-fw text-danger" aria-hidden="true"></i> @endif
        {{ $label }}
    </label>
    <div class="col-md-6">
        <div class="input-group">
            @error($name)
            <label class="input-group-text bg-danger text-white">
                <i class="fa fa-exclamation-triangle"></i>
            </label>
            @enderror

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

            <input
                    type="number"
                    step="0.01"
                    name="{{ $name }}"
                    id="{{ $name }}"
                    value="{{ $value ?? old($name) }}"
                    class="form-control @error($name) is-invalid @enderror"
                    autocomplete="off"
                    {{-- If the override checkbox is NOT ticked, and override is enabled, disable this --}}
                    @disabled(($overrideName && !$isOverridden) || $readonly)
                    @readonly($readonly)
                    {{ $attributes }}
            />

            {{-- Percentage Symbol --}}
            <span class="input-group-text">%</span>
        </div>
    </div>
</div>
