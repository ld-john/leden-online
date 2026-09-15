@props(['label', 'value' => null])
<div class="col-md-2">
    <p class="text-muted mb-1 mb-md-3">{{ $label }}</p>
</div>
<div class="col-md-4">
    <p class="fw-bold mb-3">{{ $value ?: '--' }}</p>
</div>
