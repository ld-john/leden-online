<div class="mb-4">
    @if($order)
        <div class="btn-group mb-4">
            <a href="{{ route('order.pdf', $order->id) }}" class="btn btn-secondary">Download Order PDF</a>
            <a href="{{ route('order.show', $order->id) }}" class="btn btn-primary">View Order</a>
        </div>
    @endif

    <form wire:submit="saveOrder" method="POST" enctype="multipart/form-data" onsubmit="this.querySelector('button[type=submit]').disabled = true;">
        @if($errors->count())
            <div class="alert alert-danger alert-dismissible fade show m-5">
                <h4 class="alert-heading"><i class="fa fa-exclamation-triangle"></i> There are some issues.</h4>
                <hr>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="accordion" id="accordionExample">
            <x-accordion-item id="customer" title="Customer Information" :open="true">
                @include('order.partials.customer')
            </x-accordion-item>
            <x-accordion-item id="company" title="Company">
                @include('order.partials.company')
            </x-accordion-item>
            <x-accordion-item id="vehicle" title="Vehicle">
                @include('order.partials.vehicle')
            </x-accordion-item>
            <x-accordion-item id="factory-fit" title="Factory Fit Options">
                @include('partials.factory-fit')
            </x-accordion-item>
            <x-accordion-item id="dealer-fit" title="Dealer Fit Options">
                @include('partials.dealer-fit')
            </x-accordion-item>
            <x-accordion-item id="cost" title="Cost Breakdown">
                @include('order.partials.cost')
            </x-accordion-item>
            <x-accordion-item id="finance" title="Finance Information">
                @include('order.partials.finance')
            </x-accordion-item>
            <x-accordion-item id="invoice" title="Invoicing Information">
                @include('order.partials.invoice')
            </x-accordion-item>
            <x-accordion-item id="delivery" title="Delivery Information">
                @include('order.partials.delivery')
            </x-accordion-item>
            <x-accordion-item id="additional" title="Additional Information">
                @include('order.partials.additional')
            </x-accordion-item>
            <x-accordion-item id="bond" title="Bond Information">
                @include('order.partials.bond')
            </x-accordion-item>
        </div>

        <div class="mt-4 text-right">
            <button type="submit" class="btn btn-primary" id="goButton" wire:target="saveOrder" wire:loading.attr="disabled">
                <span wire:loading.remove wire:target="saveOrder">Save Order</span>

                <span wire:loading wire:target="saveOrder">
                    <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                    Saving...
                </span>
            </button>
        </div>
    </form>
</div>
