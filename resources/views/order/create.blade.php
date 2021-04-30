@extends('layouts.main', [
    'title' => $title,
    'activePage' => $activePage
    ])

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Content Row -->
        <div class="row justify-content-center">

            <!-- Doughnut Chart -->
            <div class="col-lg-10">
                    <h1 class="h3 mb-4 text-gray-800">Create Order</h1>
                  @if (!empty(session('successMsg')))
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <strong>{{ session('successMsg') }}</strong>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        </div>
                    </div>
                @endif
                @if (!empty($errorMsg))
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>{{ $errorMsg }}</strong>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        </div>
                    </div>
                @endif
                @if($errors->all())
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-danger" role="alert">
                                @foreach($errors->all() as $message)
                                    @if($message)
                                        {!! $message !!}<br/>
                                    @endif

                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                @include('partials.order-form');

            </div>

        </div>
        <!-- /.container-fluid -->

        @endsection

        @push('custom-scripts')
            <script>
                //debugger;
                $('.discount').change(function () {

                    let value = 0
                    $('.discount').each(function () {
                        if ($(this).val()) {
                            value = value + parseFloat($(this).val())
                        }
                    })
                    $('#total_discount').val(parseFloat(value).toFixed(2));
                })

                $('.invoice-total, #list_price, #metallic_paint, #dealer_discount, #manufacturer_discount, #first_reg_fee, #rfl_cost').change(function () {
                    let value = 0;

                    value += parseFloat($('#list_price').val());
                    value += parseFloat($('#metallic_paint').val());

                    let dealerDiscount = (value / 100) * parseFloat($('#dealer_discount').val());
                    let manufacturerSupport = (value / 100) * parseFloat($('#manufacturer_discount').val());

                    value -= dealerDiscount;
                    value -= manufacturerSupport;

                    $('.invoice-total').each(function () {
                        console.log(this.tagName);

                        let itemTotal = 0;
                        let discount = 0;
                        switch (this.tagName){
                            case 'SELECT':
                                $(this).find(':selected').each(function() {
                                    itemTotal += parseFloat($(this).data('cost'));
                                });
                                break;
                            case 'INPUT':
                                itemTotal += parseFloat($(this).val());
                                break;
                        }

                        if(this.name == 'factory_option[]'){
                            discount =  (itemTotal / 100) * (
                                parseFloat($('#dealer_discount').val()) +
                                parseFloat($('#manufacturer_discount').val())
                            );
                        }

                        value += itemTotal;
                        value -= discount;
                    })

                    let vat = (value / 100) * 20;
                    let invoice_value = value + vat + parseFloat($('#first_reg_fee').val()) + parseFloat($('#rfl_cost').val());


                    $('#invoice_value').val(invoice_value.toFixed(2));
                })

                $(function () {
                    $('.btn-attachment-delete').click(function (e) {
                        e.preventDefault();
                        let uploadId = $(this).data('upload-id');
                        console.log(uploadId);

                        let link = $(this).prop('href');
                        let message = $('#attachment_message');

                        $.get(link)
                            .done(function (r) {
                                $('#attachment' + uploadId).html(r.msg);
                            })
                            .fail(function () {
                                message.html('Failed to delete');
                            });
                    })
                })
            </script>
    @endpush
