<div {{ $attributes->merge(['class' => 'row mb-1']) }}>
    <div class="col">
        <p>{{$name}}</p>
    </div>
    <div class="col-4 text-right">
        <p class="font-weight-bold">
            {{ $value < 0 ? '-' : '' }}£{{ number_format(abs($value), 2, '.', ',') }}
        </p>
    </div>
</div>
