<!DOCTYPE html>
<html lang="en">

<head>
    <title>Leden Order - # {{$order->id}}</title>

    <style>
        body {
            font-family:Arial, Helvetica, sans-serif;
            color: #858796;
            font-size: 10px;
        }
        .contents tr th, .details tr th {
            padding: 5px 20px;
            background: #1f3458;
            color: #ffffff;
        }

        .contents tr td, .details tr td {
            padding: 5px 20px;
        }

        .contents tr td, .details tr td {
            border-bottom: 1px solid #e3e6f0;
        }

        .contents tr td:first-child, .details tr td {
            border-left: 1px solid #e3e6f0;
        }

        .contents tr td:last-child {
            border-right: 1px solid #e3e6f0;
        }

        .contents tr:nth-child(odd), .details tr:nth-child(odd) {
            background: #f8f9fc;
        }

        .details tr td {
            border-right: 1px solid #e3e6f0;
        }

    </style>
</head>

<table cellspacing="0" cellpadding="0" border="0" width="100%" align="center">

    <tr>
        <td style="padding:40px 0px 0px 0px;">
            <table cellspacing="0" cellpadding="0" border="0" width="100%">
                <tr>
                    <td valign="middle" width="20%">
                        <img src="{{asset('images/leden-group-ltd.png')}}" width="90%" height="auto" style="display: block;" />
                    </td>
                    <td valign="top" width="20%">
                        <strong>Delivery Details:</strong><br>
                        {!! implode('<br>', $deliveryAddress) !!}
                    </td>
                    <td valign="top" width="20%">
                        <strong>Invoice Details:</strong><br>
                        {!! implode('<br>', $invoiceAddress) !!}
                    </td>
                    <td valign="top" width="20%">
                        <strong>Registration Details:</strong><br>
                        {!! implode('<br>', $registrationAddress) !!}
                    </td>
                    <td valign="top" width="20%">
                        <strong>Dealer Address:</strong><br>
                        {!! implode('<br>', $dealerAddress) !!}
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td style="padding: 40px 0px 0px 0px;">
            <table cellspacing="0" cellpadding="0" border="0" width="100%" class="details">
                <tr>
                    <th colspan="3" style="text-align: left;">
                        Vehicle Details
                    </th>
                </tr>
                {!! $vehicleDetailsHtml !!}
            </table>
        </td>
    </tr>
    <tr>
        <td style="padding: 40px 0px 0px 0px;">
            <table cellspacing="0" cellpadding="0" border="0" width="100%">
                <tr>
                    <td width="48%" valign="top">
                        <table cellspacing="0" cellpadding="0" border="0" width="100%" class="contents">
                            <tr>
                                <th style="text-align: left;">
                                    Factory Option
                                </th>
                                <th style="text-align: right;">
                                    Cost
                                </th>
                            </tr>

                            @foreach ($factory_fit_options as $factory_option) {
                            <tr>
                                <td>
                                    {{$factory_option->option_name}}
                                </td>
                                <td style="text-align: right;">
                                    £{{number_format($factory_option->option_price, '2', '.', ',')}}
                                </td>
                            </tr>
                            }
                            @endforeach

                        </table>
                    </td>
                    <td></td>
                    <td width="48%" valign="top">
                        <table cellspacing="0" cellpadding="0" border="0" width="100%" class="contents">
                            <tr>
                                <th style="text-align: left;">
                                    Dealer Fit Option
                                </th>
                                <th style="text-align: right;">
                                    Cost
                                </th>
                            </tr>

                            @foreach ($dealer_fit_options as $dealer_option) {
                            <tr>
                                <td>
                                    {{$dealer_option->option_name}}
                                </td>
                                <td style="text-align: right;">
                                    £{{number_format($dealer_option->option_price, '2', '.', ',')}}
                                </td>
                            </tr>
                            }
                            @endforeach

                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td style="padding: 40px 0px 40px 0px;">
            <table cellspacing="0" cellpadding="0" border="0" width="100%">
                <tr>
                    <td width="56%" valign="top">
                        <table cellspacing="0" cellpadding="0" border="0" width="100%" class="contents">
                            <tr>
                                <th style="text-align: left;">
                                    Cost Breakdown
                                </th>
                                <th style="text-align: right;">
                                    Cost (£)
                                </th>
                            </tr>
                            <tr>
                                <td>
                                    List Price
                                </td>
                                <td style="text-align: right;">
                                    £{{number_format($order->vehicle->list_price, '2', '.', ',')}}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Metallic Paint
                                </td>
                                <td style="text-align: right;">
                                    £{{number_format($order->vehicle->metallic_paint, '2', '.', ',')}}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Dealer Discount
                                </td>
                                <td style="text-align: right;">
                                    {{number_format($order->invoice->dealer_discount, '3', '.', ',')}}%
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Manufacturer Discount
                                </td>
                                <td style="text-align: right;">
                                    {{number_format($order->invoice->manufacturer_discount, '3', '.', ',')}}%
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Manufacturer Delivery Cost
                                </td>
                                <td style="text-align: right;">
                                    £{{number_format($order->invoice->manufacturer_delivery_cost, '2', '.', ',')}}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    1st Reg Fee
                                </td>
                                <td style="text-align: right;">
                                    £{{number_format($order->vehicle->first_reg_fee, '2', '.', ',')}}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    RFL
                                </td>
                                <td style="text-align: right;">
                                    £{{number_format($order->vehicle->rfl_cost, '2', '.', ',')}}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Onward Delivery
                                </td>
                                <td style="text-align: right;">
                                    £{{number_format($order->vehicle->onward_delivery, '2', '.', ',')}}
                                </td>
                            </tr>
                        </table>
                        <table cellspacing="0" cellpadding="0" border="0" width="100%" class="contents" style="margin-top: 10px">
                            <tr>
                                <th colspan="2">For Internal Use</th>
                            </tr>
                            <tr>
                                <td>Done by:</td>
                                <td>Sent: </td>
                            </tr>
                            <tr>
                                <td>Checked by:</td>
                                <td>Confirmed</td>
                            </tr>
                            <tr>
                                <td>AOC:</td>
                                <td>Orbit:</td>
                            </tr>
                        </table>
                        <table cellspacing="0" cellpadding="0" border="0" width="100%" class="contents" style="margin-top: 10px">
                            <tr>
                                <th colspan="2"></th>
                            </tr>
                            <tr>
                                <td>
                                    FIN: {{$order->fin_number}}
                                </td>
                                <td>
                                    Deal: {{$order->deal_number}}
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td></td>
                    <td width="40%" valign="top">
                        <table cellspacing="0" cellpadding="0" border="0" width="100%" class="pricing">
                            <tr>
                                <td style="padding: 20px 20px 10px 20px;">
                                    Subtotal
                                </td>
                                <td style="text-align: right; padding: 20px 20px 10px 20px;">
                                    £{{number_format($subtotal, '2', '.', ',')}}
                                </td>
                            </tr>
                            <tr>
                                <td style="border-bottom: 1px solid #e3e6f0; padding: 10px 20px 20px 20px;">
                                    + VAT
                                </td>
                                <td style="border-bottom: 1px solid #e3e6f0; text-align: right; padding: 10px 20px 20px 20px;">
                                    £{{number_format($vat, '2', '.', ',')}}
                                </td>
                            </tr>
                            <tr>
                                <td  style="border-bottom: 1px solid #e3e6f0; padding: 10px 20px 20px 20px;">
                                    <strong>Total</strong>
                                </td>
                                <td  style="border-bottom: 1px solid #e3e6f0; text-align: right; padding: 10px 20px 20px 20px;">
                                    <strong>£{{number_format($total, '2', '.', ',')}}</strong>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 20px 20px 10px 20px;">
                                    Please invoice funder for
                                </td>
                                <td style="text-align: right; padding: 20px 20px 10px 20px;">
                                    £{{number_format($order->invoice->invoice_funder_for, '2', '.', ',')}}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="padding: 20px 20px 10px 20px;">
                                    <strong>We will invoice you for the difference</strong>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 20px 20px 10px 20px;">
                                    inc VAT
                                </td>
                                <td style="text-align: right; padding: 20px 20px 10px 20px;">
                                    £{{number_format($order->invoiceDifferenceIncVat(), '2', '.', ',')}}
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 20px 20px 10px 20px;">
                                    exc VAT
                                </td>
                                <td style="text-align: right; padding: 20px 20px 10px 20px;">
                                    £{{number_format($order->invoiceDifferenceExVat(), '2', '.', ',')}}
                                </td>
                            </tr>
                            <tr>
                                <td  style="border-top: 1px solid #e3e6f0; padding: 20px 20px 10px 20px;">
                                    Commission to Finance Company
                                </td>
                                <td  style="border-top: 1px solid #e3e6f0; text-align: right; padding: 20px 20px 10px 20px;">
                                    £{{number_format($order->invoice->commission_to_finance_company, '2', '.', ',')}}</td>
                            </tr>
                            <tr>
                                <td style="padding: 20px 20px 10px 20px;">
                                    Invoice to Broker
                                </td>
                                <td style="text-align: right; padding: 20px 20px 10px 20px;">
                                    £{{number_format($order->invoice->invoice_value_to_broker, '2', '.', ',')}}
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 20px 20px 10px 20px;">
                                    Commission to Broker
                                </td>
                                <td style="text-align: right; padding: 20px 20px 10px 20px;">
                                    £{{number_format($order->invoice->commission_to_broker, '2', '.', ',')}}
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td bgcolor="#1f3458" style="text-align: center; padding: 5px 20px; font-size: 14px; color: #ffffff;">
            @php ($time = date('Y-m-d'))
            Copyright &copy; {{\Carbon\Carbon::createFromFormat('Y-m-d', $time)->year}} The Leden Group Limited, All rights reserved.
        </td>
    </tr>
</table>
