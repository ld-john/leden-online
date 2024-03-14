<div class="mb-4">
    @if($order)
        <div class="btn-group mb-4">
            <a href="{{ route('order.pdf', $order->id) }}" class="btn btn-secondary">Download Order PDF</a>
            <a href="{{ route('order.show', $order->id) }}" class="btn btn-primary">View Order</a>
        </div>
    @endif
    <form wire:submit="orderFormSubmit" method="POST" enctype="multipart/form-data">
        @if($errors->count())
            <div class="alert alert-danger alert-dismissible fade show m-5">
                <h4 class="alert-heading"><i class="fa fa-exclamation-triangle"></i> There are some issues.</h4>
                <hr>
                <ul>
                    {!! implode($errors->all('<li>:message</li>')) !!}
                </ul>
            </div>
        @endif

        <div class="accordion" id="accordionExample">
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingCustomer">
                    <button
                            class="accordion-button"
                            type="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#collapseCustomer"
                            aria-expanded="true"
                            aria-controls="collapseCustomer"
                    >
                        Customer Information
                    </button>
                </h2>
                <div
                        id="collapseCustomer"
                        class="accordion-collapse collapse show"
                        aria-labelledby="headingCustomer"
                        wire:ignore.self
                >
                    <div class="accordion-body">
                        @include('order.partials.customer')
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingCompany">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseCompany" aria-expanded="false" aria-controls="collapseCompany">
                        Company
                    </button>
                </h2>
                <div id="collapseCompany" class="accordion-collapse collapse" aria-labelledby="headingCompany" wire:ignore.self>
                    <div class="accordion-body">
                        @include('order.partials.company')
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingVehicle">
                    <button
                            class="accordion-button collapsed"
                            type="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#collapseVehicle"
                            aria-expanded="true"
                            aria-controls="collapseVehicle"
                    >
                        Vehicle Information
                    </button>
                </h2>
                <div
                        id="collapseVehicle"
                        class="accordion-collapse collapse"
                        aria-labelledby="headingVehicle"
                        wire:ignore.self
                >
                    <div class="accordion-body">
                        @include('order.partials.vehicle')
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingFactoryFit">
                    <button
                            class="accordion-button collapsed"
                            type="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#collapseFactoryFit"
                            aria-expanded="true"
                            aria-controls="collapseFactoryFit"
                    >
                        Factory Fit Options
                    </button>
                </h2>
                <div
                        id="collapseFactoryFit"
                        class="accordion-collapse collapse"
                        aria-labelledby="headingFactoryFit"
                        wire:ignore.self
                >
                    <div class="accordion-body">
                        @include('partials.factory-fit')
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingDealerFit">
                    <button
                            class="accordion-button collapsed"
                            type="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#collapseDealerFit"
                            aria-expanded="true"
                            aria-controls="collapseDealerFit"
                    >
                        Dealer Fit
                    </button>
                </h2>
                <div
                        id="collapseDealerFit"
                        class="accordion-collapse collapse"
                        aria-labelledby="headingDealerFit"
                        wire:ignore.self
                >
                    <div class="accordion-body">
                        @include('partials.dealer-fit')
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingCost">
                    <button
                            class="accordion-button collapsed"
                            type="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#collapseCost"
                            aria-expanded="true"
                            aria-controls="collapseCost"
                    >
                        Cost Breakdown
                    </button>
                </h2>
                <div
                        id="collapseCost"
                        class="accordion-collapse collapse"
                        aria-labelledby="headingCost"
                        wire:ignore.self
                >
                    <div class="accordion-body">

                        @include('order.partials.cost')
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingFinanceInfo">
                    <button
                            class="accordion-button collapsed"
                            type="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#collapseFinanceInfo"
                            aria-expanded="false"
                            aria-controls="collapseFinanceInfo"
                    >
                        Finance Information
                    </button>
                </h2>
                <div
                        id="collapseFinanceInfo"
                        class="accordion-collapse collapse"
                        aria-labelledby="headingFinanceInfo"
                        wire:ignore.self
                >
                    <div class="accordion-body">
                        @include('order.partials.finance')
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingInvoice">
                    <button
                            class="accordion-button collapsed"
                            type="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#collapseInvoice"
                            aria-expanded="true"
                            aria-controls="collapseInvoice"
                    >
                        Invoicing Information
                    </button>
                </h2>
                <div
                        id="collapseInvoice"
                        class="accordion-collapse collapse"
                        aria-labelledby="headingInvoice"
                        wire:ignore.self
                >
                    <div class="accordion-body">
                        @include('order.partials.invoice')
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingDelivery">
                    <button
                            class="accordion-button collapsed"
                            type="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#collapseDelivery"
                            aria-expanded="true"
                            aria-controls="collapseDelivery"
                    >
                        Delivery Information
                    </button>
                </h2>
                <div
                        id="collapseDelivery"
                        class="accordion-collapse collapse"
                        aria-labelledby="headingDelivery"
                        wire:ignore.self
                >
                    <div class="accordion-body">
                        @include('order.partials.delivery')
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingAdditional">
                    <button
                            class="accordion-button collapsed"
                            type="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#collapseAdditional"
                            aria-expanded="true"
                            aria-controls="collapseAdditional"
                    >
                        Additional Information
                    </button>
                </h2>
                <div
                        id="collapseAdditional"
                        class="accordion-collapse collapse"
                        aria-labelledby="headingAdditional"
                        wire:ignore.self
                >
                    <div class="accordion-body">
                        @include('order.partials.additional')
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4 text-right">
            <button class="btn btn-primary" id="goButton" type="submit">Save Order</button>
        </div>
    </form>
</div>

@push('custom-scripts')

    <script>
      $( function (){
        $('#goButton').click(function(){
          window.scrollTo(0,0);
        });

      })
    </script>

    <script>
      let dealer_discount = document.querySelector('#dealer_discount')
      let manufacturer_discount = document.querySelector('#manufacturer_discount')
      let total_discount = document.querySelector('#total_discount')
      let list_price = document.querySelector('#list_price')
      let metallic_paint = document.querySelector('#metallic_paint')
      let manufacturer_delivery_cost = document.querySelector('#manufacturer_delivery_cost')
      let first_reg_fee = document.querySelector('#first_reg_fee')
      let rfl_cost = document.querySelector('#rfl_cost')
      let onward_delivery = document.querySelector('#onward_delivery')
      let invoice_funder_for = document.querySelector('#invoice_funder_for')
      let invoiceValue = document.querySelector('#invoice_value')
      let metallic_paint_discount = document.querySelector('#metallic_paint_discount')
      let metallic_paint_raw = document.querySelector('#metallic_paint_discount_raw')

      window.addEventListener('dealerDiscountChanged', invoiceValueChange)

      document.addEventListener('DOMContentLoaded', invoiceValueChange)

      function invoiceValueChange()
      {

        let vatRate = 20;

        let discountSum = parseFloat(dealer_discount.value) + parseFloat(manufacturer_discount.value)
        let discountMetallicPaint = parseFloat(dealer_discount.value) + parseFloat(manufacturer_discount.value)

        if (metallic_paint_raw.value !== '0') {
          discountMetallicPaint = parseFloat(metallic_paint_raw.value)
        }

        metallic_paint_discount.value = discountMetallicPaint
        total_discount.value = discountSum

        let InvoiceListPrice = parseInt(list_price.value)
        let InvoiceMetallicPaint = parseInt(metallic_paint.value)
        let InvoiceListPriceDiscount = ( parseInt( InvoiceListPrice ) / 100) * parseInt(discountSum)
        let InvoiceMetallicPaintDiscount = ( parseInt( InvoiceMetallicPaint ) / 100 ) * parseInt(discountMetallicPaint)
        let InvoiceSum3 =
          (InvoiceListPrice -
            InvoiceListPriceDiscount) +
          ( InvoiceMetallicPaint - InvoiceMetallicPaintDiscount ) +
          parseInt(onward_delivery.value) +
          parseInt(manufacturer_delivery_cost.value);
        let InvoiceSum4 =
          parseInt(first_reg_fee.value) +
          parseInt(rfl_cost.value);
        if (InvoiceSum3) {
          invoiceValue.value = ( ( InvoiceSum3 / 100 ) * vatRate ) + InvoiceSum3 + InvoiceSum4;
        }

      }

    </script>

@endpush
