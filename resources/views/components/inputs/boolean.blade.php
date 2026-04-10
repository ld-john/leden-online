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
        <select
                wire:model.live="{{ $name }}"
                name="{{ $name }}"
                id="{{ $name }}"
                class="form-control"
                {{ $attributes }}
        >
            <option value="0">No</option>
            <option value="1">Yes</option>
        </select>
    </div>
</div>
