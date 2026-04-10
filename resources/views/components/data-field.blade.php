<div class="col-md-2">
    <p>{{ $label }}</p>
</div>
<div class="col-md-4">
    <p class="font-weight-bold">{{ $slot->isEmpty() ? $value : $slot }}</p>
</div>
