@props([
    'label',
    'name',
    'options' => [],
    'optionLabel' => 'name', // Which object property to show as text
    'optionValue' => 'id',   // Which object property to use as value
    'placeholder' => 'Choose...',
    'addUrl' => null,
    'required' => false,
    'overrideName' => null,
    'isOverridden' => false
])

<div class="form-group row">
    <label class="col-md-2 col-form-label" for="{{ $name }}">
        @if($required)
            <i class="fa fa-asterisk fa-fw text-danger" aria-hidden="true"></i>
        @endif
        {{ $label }}
    </label>

    <div class="col-md-6">
        <div class="input-group mb-3">
            @error($name)
            <label class="input-group-text bg-danger text-white" for="{{ $name }}">
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

            <select
                wire:model.live="{{ $name }}"
                name="{{ $name }}"
                id="{{ $name }}"
                class="form-select"
                @if(count($options) === 0) disabled @endif
                {{ $attributes }}
                @disabled(($overrideName && !$isOverridden))
            >
                @if($placeholder)
                    <option value="">{{ $placeholder }}</option>
                @endif
                @foreach($options as $option)
                        @php
                            // Handles Eloquent Models, Objects, Enums, Key-Value Arrays & Scalar Arrays safely
                            if (is_object($option)) {
                                $val = method_exists($option, 'value') ? $option->value : ($option->$optionValue ?? $option->value);
                                $lbl = method_exists($option, 'label') ? $option->label() : ($option->$optionLabel ?? $option->name);
                            } elseif (is_array($option)) {
                                $val = $option[$optionValue] ?? $key;
                                $lbl = $option[$optionLabel] ?? $option;
                            } else {
                                $val = is_numeric($key) ? $option : $key;
                                $lbl = $option;
                            }
                        @endphp

                        <option value="{{ $val }}">{{ $lbl }}</option>
                @endforeach
            </select>
        </div>
        @error($name)
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    @if($addUrl)
        <div class="col-md-2">
            {{-- Target blank prevents data loss on current form --}}
            <a class="btn btn-secondary" href="{{ $addUrl }}" target="_blank" data-toggle="tooltip" title="Add New">
                <i class="fa-solid fa-plus"></i>
            </a>
        </div>
    @endif
</div>
