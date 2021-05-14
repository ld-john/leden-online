

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
                        <button type="button" class="btn btn-link" data-toggle="collapse" data-target="#collapseCustomer" aria-expanded="true" aria-controls="collapseCustomer">
                            Customer Information
                        </button>
                    </h5>
                </div>

                <div id="collapseCustomer" class="collapse show" aria-labelledby="headingCustomer" data-parent="#mainOrderForm">
                    <div class="card-body">
                        @include('order.partials.customer')
                    </div>
                </div>
            </div>

            {{-- Vehicle --}}
            <div class="card mb-3">
                <div class="card-header" id="headingVehicle">
                    <h5 class="mb-0">
                        <button type="button" class="btn btn-link" data-toggle="collapse" data-target="#collapseVehicle" aria-expanded="true" aria-controls="collapseVehicle">
                            Vehicle Information
                        </button>
                    </h5>
                </div>

                <div id="collapseVehicle" class="collapse @if( $name || $customer_id) show @endif" aria-labelledby="headingVehicle" data-parent="#mainOrderForm">
                    <div class="card-body">
                        @include('order.partials.vehicle')
                    </div>
                </div>
            </div>

            {{-- Factory Fit --}}
            <div class="card mb-3">
                <div class="card-header" id="headingFactoryFit">
                    <h5 class="mb-0">
                        <button type="button" class="btn btn-link" data-toggle="collapse" data-target="#collapseFactoryFit" aria-expanded="true" aria-controls="collapseFactoryFit">
                            Factory Fit Options
                        </button>
                    </h5>
                </div>

                <div id="collapseFactoryFit" class="collapse @if( $status) show @endif" aria-labelledby="headingFactoryFit" data-parent="#mainOrderForm">
                    <div class="card-body">
                        @include('order.partials.factoryFit')
                    </div>
                </div>
            </div>

            {{-- Dealer Fit --}}
            <div class="card mb-3">
                <div class="card-header" id="headingDealerFit">
                    <h5 class="mb-0">
                        <button type="button" class="btn btn-link" data-toggle="collapse" data-target="#collapseDealerFit" aria-expanded="true" aria-controls="collapseDealerFit">
                            Dealer Fit
                        </button>
                    </h5>
                </div>

                <div id="collapseDealerFit" class="collapse" aria-labelledby="headingDealerFit" data-parent="#mainOrderForm">
                    <div class="card-body">
                        @include('order.partials.dealerFit')
                    </div>
                </div>
            </div>

            {{-- Company Details --}}
            <div class="card mb-3">
                <div class="card-header" id="headingCompany">
                    <h5 class="mb-0">
                        <button type="button" class="btn btn-link" data-toggle="collapse" data-target="#collapseCompany" aria-expanded="true" aria-controls="collapseCompany">
                            Company
                        </button>
                    </h5>
                </div>

                <div id="collapseCompany" class="collapse" aria-labelledby="headingCompany" data-parent="#mainOrderForm">
                    <div class="card-body">
                        @include('order.partials.company')
                    </div>
                </div>
            </div>

            {{-- Cost Breakdown --}}
            <div class="card mb-3">
                <div class="card-header" id="headingCost">
                    <h5 class="mb-0">
                        <button type="button" class="btn btn-link" data-toggle="collapse" data-target="#collapseCost" aria-expanded="true" aria-controls="collapseCost">
                            Cost Breakdown
                        </button>
                    </h5>
                </div>

                <div id="collapseCost" class="collapse" aria-labelledby="headingCost" data-parent="#mainOrderForm">
                    <div class="card-body">
                        @include('order.partials.cost')
                    </div>
                </div>
            </div>

            {{-- Invoice --}}
            <div class="card mb-3">
                <div class="card-header" id="headingInvoice">
                    <h5 class="mb-0">
                        <button type="button" class="btn btn-link" data-toggle="collapse" data-target="#collapseInvoice" aria-expanded="true" aria-controls="collapseInvoice">
                            Invoicing Information
                        </button>
                    </h5>
                </div>

                <div id="collapseInvoice" class="collapse" aria-labelledby="headingInvoice" data-parent="#mainOrderForm">
                    <div class="card-body">
                        @include('order.partials.invoice')
                    </div>
                </div>
            </div>

            {{-- Delivery --}}
            <div class="card mb-3">
                <div class="card-header" id="headingDelivery">
                    <h5 class="mb-0">
                        <button type="button" class="btn btn-link" data-toggle="collapse" data-target="#collapseDelivery" aria-expanded="true" aria-controls="collapseDelivery">
                            Delivery Information
                        </button>
                    </h5>
                </div>

                <div id="collapseDelivery" class="collapse" aria-labelledby="headingDelivery" data-parent="#mainOrderForm">
                    <div class="card-body">
                        @include('order.partials.delivery')
                    </div>
                    <div class="col-md-5">
                        <div class="row dealer-row">
                            <div class="col-md-6">
                                <input wire:model="dealer_fit_name_manual_add" type="text" class="form-control" placeholder="e.g. LED Lights"/>
                                @error('dealer_fit_name_manual_add') <div class="alert alert-danger">{!! $message !!} </div> @enderror
                            </div>
                            <div class="col-md-6">
                                <input wire:model="dealer_fit_price_manual_add" type="number" step=".01" class="form-control"
                                       placeholder="e.g. 20.99"/>
                                @error('dealer_fit_price_manual_add') <div class="alert alert-danger">{!! $message !!} </div> @enderror
                            </div>
                        </div>
                        <div class="add-dealer-con mt-4">
                            <button wire:click="newDealerFit" class="btn btn-sm btn-secondary" id="add-dealer-option" type="button">
                                Add New Option
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-l-blue">Company Details</h6>
            </div>
            <div class="card-body">
                <div class="form-group row">
                    <label for="dealership" class="col-md-2 col-form-label">Dealership</label>
                    <div class="col-md-6">
                        <select wire:model="dealership" name="dealership" id="dealership" class="form-control">
                            <option value="">Select Dealership</option>
                            @foreach ($dealers as $dealer)
                                <option value="{{ $dealer->id }}">{{ $dealer->company_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="dealership" class="col-md-2 col-form-label">Registration Company</label>
                    <div class="col-md-6">
                        <select wire:model="registration_company" name="registration_company" id="registration_company" class="form-control">
                            <option value="">Select Registration Company</option>
                            @foreach ($registration_companies as $company)
                                <option value="{{ $company->id }}"
                                        @if ($company->id == $order_details->registration_company) selected @endif>{{ $company->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="invoice_company" class="col-md-2 col-form-label">Invoice Company</label>
                    <div class="col-md-6">
                        <select wire:model="invoice_company" name="invoice_company" id="invoice_company" class="form-control">
                            <option value="">Select Invoice Company</option>
                            @foreach ($invoice_companies as $company)
                                <option value="{{ $company->id }}"
                                        @if ($company->id == $order_details->invoice_company) selected @endif>{{ $company->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

            </div>
            <!-- Card Header -->
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-l-blue">Cost Breakdown</h6>
            </div>
            <!-- Card Body -->
            <div class="card-body">
                <div class="form-group row">
                    <label for="list_price" class="col-md-2 col-form-label">List Price (£)</label>
                    <div class="col-md-6">
                        <input wire:model="list_price" type="number" name="list_price" id="list_price" step=".01"
                               class="form-control" autocomplete="off" placeholder="e.g. 10985.24" onchange="invoiceValueChange()"/>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="metallic_paint" class="col-md-2 col-form-label">Metallic Paint (£)</label>
                    <div class="col-md-6">
                        <input wire:model="metallic_paint" type="number" name="metallic_paint" id="metallic_paint" step=".01"
                               class="form-control" autocomplete="off" placeholder="e.g. 389.55" onchange="invoiceValueChange()" />
                    </div>
                </div>

                <div class="form-group row">
                    <label for="dealer_discount" class="col-md-2 col-form-label">Dealer Discount (%)</label>
                    <div class="col-md-6">
                        <input wire:model="dealer_discount" type="number" name="dealer_discount" id="dealer_discount" step=".01"
                               class="form-control discount" autocomplete="off" placeholder="e.g. 2.857" onchange="invoiceValueChange()" />
                    </div>
                </div>

                <div class="form-group row">
                    <label for="manufacturer_discount" class="col-md-2 col-form-label">Manufacturer Discount
                        (%)</label>
                    <div class="col-md-6">
                        <input wire:model="manufacturer_discount" type="number" name="manufacturer_discount" id="manufacturer_discount"
                               step=".01" class="form-control discount" autocomplete="off"
                               placeholder="e.g. 3.879" onchange="invoiceValueChange()"/>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="total_discount" class="col-md-2 col-form-label">Total Discount (%)</label>
                    <div class="col-md-6">
                        <input type="number" name="total_discount" id="total_discount" class="form-control"
                               placeholder="Dealer + Manufacturer"
                               disabled/>
                    </div>
                </div>

                <div id="collapseAdditional" class="collapse" aria-labelledby="headingAdditional" data-parent="#mainOrderForm">
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
