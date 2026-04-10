<div>
    @foreach($models as $model)
        <livewire:customer.dealer-discount-field :dealer="$dealer" :vehicleModel="$model" />
    @endforeach
</div>
