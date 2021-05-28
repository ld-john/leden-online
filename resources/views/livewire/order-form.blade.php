

<div>
    <form wire:submit.prevent="orderFormSubmit" method="POST" enctype="multipart/form-data">
        @csrf
        @if ($successMsg)
            <div class="alert alert-success" role="alert">
                {{$successMsg}}
            </div>
        @endif

        @if($errors->count())
            <div class="alert alert-danger alert-dismissible fade show m-5">
                <h4 class="alert-heading"><i class="fa fa-exclamation-triangle"></i> There are some issues.</h4>
                <hr>
                <ul>
                    {!! implode($errors->all('<li>:message</li>')) !!}
                </ul>
            </div>
        @endif

        <div id="mainOrderForm">

            {{-- Customer --}}
            <div class="card mb-3">
                <div class="card-header" id="headingCustomer">
                    <h5 class="mb-0">
                        <button type="button" wire:click="$set('showCustomerInfo' , {{!$showCustomerInfo}})" class="btn btn-link" data-toggle="collapse" data-target="#collapseCustomer" aria-expanded="true" aria-controls="collapseCustomer">
                            Customer Information
                        </button>
                    </h5>
                </div>

                <div id="collapseCustomer" class="collapse @if( $showCustomerInfo ) show @endif" aria-labelledby="headingCustomer">
                    <div class="card-body">
                        @include('order.partials.customer')
                    </div>
                </div>
            </div>

            {{-- Vehicle --}}
            <div class="card mb-3">
                <div class="card-header" id="headingVehicle">
                    <h5 class="mb-0">
                        <button type="button" class="btn btn-link" wire:click="$set('showVehicleInfo' , {{!$showVehicleInfo}})" data-toggle="collapse" data-target="#collapseVehicle" aria-expanded="true" aria-controls="collapseVehicle">
                            Vehicle Information
                        </button>
                    </h5>
                </div>

                <div id="collapseVehicle" class="collapse @if( $showVehicleInfo ) show @endif" aria-labelledby="headingVehicle">
                    <div class="card-body">
                        @include('order.partials.vehicle')
                    </div>
                </div>
            </div>

            {{-- Factory Fit --}}
            <div class="card mb-3">
                <div class="card-header" id="headingFactoryFit">
                    <h5 class="mb-0">
                        <button type="button" class="btn btn-link" wire:click="$set('showFactoryFitOption' , {{!$showFactoryFitOptions}})" data-toggle="collapse" data-target="#collapseFactoryFit" aria-expanded="true" aria-controls="collapseFactoryFit">
                            Factory Fit Options
                        </button>
                    </h5>
                </div>

                <div id="collapseFactoryFit" class="collapse @if( $showFactoryFitOptions ) show @endif" aria-labelledby="headingFactoryFit">
                    <div class="card-body">
                        @include('order.partials.factoryFit')
                    </div>
                </div>
            </div>

            {{-- Dealer Fit --}}
            <div class="card mb-3">
                <div class="card-header" id="headingDealerFit">
                    <h5 class="mb-0">
                        <button type="button" class="btn btn-link" wire:click="$set('showDealerFitOptions' , {{!$showDealerFitOptions}})" data-toggle="collapse" data-target="#collapseDealerFit" aria-expanded="true" aria-controls="collapseDealerFit">
                            Dealer Fit
                        </button>
                    </h5>
                </div>

                <div id="collapseDealerFit" class="collapse @if( $showDealerFitOptions ) show @endif" aria-labelledby="headingDealerFit" >
                    <div class="card-body">
                        @include('order.partials.dealerFit')
                    </div>
                </div>
            </div>

            {{-- Company Details --}}
            <div class="card mb-3">
                <div class="card-header" id="headingCompany">
                    <h5 class="mb-0">
                        <button type="button" class="btn btn-link" wire:click="$set('showCompanyInfo' , {{!$showCompanyInfo}})" data-toggle="collapse" data-target="#collapseCompany" aria-expanded="true" aria-controls="collapseCompany">
                            Company
                        </button>
                    </h5>
                </div>

                <div id="collapseCompany" class="collapse @if( $showCompanyInfo ) show @endif" aria-labelledby="headingCompany">
                    <div class="card-body">
                        @include('order.partials.company')
                    </div>
                </div>
            </div>

            {{-- Cost Breakdown --}}
            <div class="card mb-3">
                <div class="card-header" id="headingCost">
                    <h5 class="mb-0">
                        <button type="button" class="btn btn-link" wire:click="$set('showCostBreakdown' , {{!$showCostBreakdown}})" data-toggle="collapse @if( $showCostBreakdown ) show @endif" data-target="#collapseCost" aria-expanded="true" aria-controls="collapseCost">
                            Cost Breakdown
                        </button>
                    </h5>
                </div>

                <div id="collapseCost" class="collapse @if( $showCostBreakdown ) show @endif" aria-labelledby="headingCost">
                    <div class="card-body">
                        @include('order.partials.cost')
                    </div>
                </div>
            </div>

            {{-- Invoice --}}
            <div class="card mb-3">
                <div class="card-header" id="headingInvoice">
                    <h5 class="mb-0">
                        <button type="button" class="btn btn-link" wire:click="$set('showInvoicingInformation' , {{!$showInvoicingInformation}})" data-toggle="collapse" data-target="#collapseInvoice" aria-expanded="true" aria-controls="collapseInvoice">
                            Invoicing Information
                        </button>
                    </h5>
                </div>

                <div id="collapseInvoice" class="collapse @if( $showInvoicingInformation ) show @endif" aria-labelledby="headingInvoice">
                    <div class="card-body">
                        @include('order.partials.invoice')
                    </div>
                </div>
            </div>


            {{-- Delivery --}}
            <div class="card mb-3">
                <div class="card-header" id="headingInvoice">
                    <h5 class="mb-0">
                        <button type="button" class="btn btn-link" wire:click="$set('showDeliveryInformation' , {{!$showDeliveryInformation}})" data-toggle="collapse" data-target="#collapseDelivery" aria-expanded="true" aria-controls="collapseDelivery">
                            Delivery Information
                        </button>
                    </h5>
                </div>

                <div id="collapseDelivery" class="collapse @if( $showDeliveryInformation ) show @endif" aria-labelledby="headingInvoice">
                    <div class="card-body">
                        @include('order.partials.delivery')
                    </div>
                </div>
            </div>

            {{-- Additonal --}}
            <div class="card mb-3">
                <div class="card-header" id="headingDelivery">
                    <h5 class="mb-0">
                        <button type="button" class="btn btn-link" wire:click="$set('showAdditionalInformation' , {{!$showAdditionalInformation}})" data-toggle="collapse" data-target="#collapseAdditional" aria-expanded="true" aria-controls="collapseAdditional">
                            Additional Information
                        </button>
                    </h5>
                </div>

                <div id="collapseAdditional" class="collapse @if( $showAdditionalInformation ) show @endif" aria-labelledby="headingAdditional">
                    <div class="card-body">
                        @include('order.partials.additional')
                    </div>
                </div>
            </div>


        </div>

        <div class="card-footer text-right">
            <button class="btn btn-primary" id="goButton" type="submit">Save Order</button>
        </div>

    </form>

</div>


@push('custom-scripts')

    <script>
        $( function (){
            $('#goButton').click(function(event){
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

        function invoiceValueChange()
        {
            let discountSum = parseInt(dealer_discount.value) + parseInt(manufacturer_discount.value)
            total_discount.value = discountSum

            let InvoiceSum1 = parseInt(list_price.value)+parseInt(metallic_paint.value)
            let InvoiceSum2 = ( parseInt( InvoiceSum1 ) / 100) * parseInt(discountSum)
            let InvoiceSum3 = InvoiceSum1 - InvoiceSum2
            let InvoiceSum4 = parseInt(manufacturer_delivery_cost.value) + parseInt(first_reg_fee.value) + parseInt(rfl_cost.value) + parseInt(onward_delivery.value)
            let InvoiceSum5 = (InvoiceSum3 + InvoiceSum4) - parseInt(invoice_funder_for.value)
            if (InvoiceSum5) {
                invoiceValue.value = InvoiceSum5
            }

        }

    </script>

@endpush
