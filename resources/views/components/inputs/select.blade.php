@props([
    'label',
    'name',
    'options' => [],
    'optionLabel' => 'name', // Which object property to show as text
    'optionValue' => 'id',   // Which object property to use as value
    'placeholder' => 'Choose...',
    'addUrl' => null,
    'required' => false,
])

<div class="form-group row">
    <label class="col-md-2 col-form-label" for="{{ $name }}">
        @if($required) <i class="fa fa-asterisk fa-fw text-danger" aria-hidden="true"></i> @endif
        {{ $label }}
    </label>

    <div class="col-md-6">
        <div class="input-group mb-3">
            @error($name)
            <label class="input-group-text bg-danger text-white" for="{{ $name }}">
                <i class="fa fa-exclamation-triangle"></i>
            </label>
            @enderror
            <select
                wire:model.live="{{ $name }}"
                name="{{ $name }}"
                id="{{ $name }}"
                class="form-select"
                @if(count($options) === 0)disabled @endif
                {{ $attributes }}
            >
                <option value="">{{ $placeholder }}</option>
                @foreach($options as $option)

                    {{-- Support both Object and Array data --}}
                    <option value="{{ is_object($option) ? $option->$optionValue : $option[$optionValue] }}">
                        {{ is_object($option) ? $option->$optionLabel : $option[$optionLabel] }}
                    </option>
                @endforeach
            </select>
        </div>
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
