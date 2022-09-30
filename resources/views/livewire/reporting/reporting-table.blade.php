<div>
    <div class="d-flex justify-content-between">
        <div class="w-25 p-3 d-flex align-items-center">
            Show
            <select wire:model="paginate" class="form-select mx-2" name="" id="">
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>
            Vehicles
        </div>
        <div class="w-75 p-3">
            @foreach($years as $year)
                <div class="d-flex space-x-2 align-items-center mb-2">
                    <p class="m-0"><strong>{{ $year }}</strong></p>
                    @foreach($months as $key => $month)
                        <a wire:click="clickMonth('{{$key}}', '{{$year}}')" class="btn btn-primary btn-sm">{{ $month }} {{ $year }}</a>
                    @endforeach
                    @foreach($quarters as $quarter)
                        <a href="" class="btn btn-secondary btn-sm">Q{{ $quarter }} {{ $year }}</a>
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
            <tr class="blue-background text-white">
                <th>Customer</th>
                <th>Delivery Date</th>
                <th>Reg Month</th>
                <th>Chassis</th>
                <th>Registration</th>
                <th>List Price</th>
                <th>Make</th>
                <th>Model</th>
                <th>Derivative</th>
                <th>Type</th>
                <th>Funder</th>
                <th>Dealer</th>
                <th>Invoice Total</th>
                <th>Invoice Difference</th>
                <th>Commission to Finance Company</th>
                <th>Invoice to Broker</th>
                <th>Broker</th>
                <th>Commission to Broker</th>
                <th>Leden Commission</th>
            </tr>
            </thead>
            <tbody>
            @forelse($vehicles as $vehicle)
                @php
                    if ($vehicle->order?->invoice->invoice_value_to_dealer) {
                        $invoice_diff = $vehicle->order?->invoice->invoice_value_to_dealer;
                    } elseif ($vehicle->order?->invoice->invoice_value_from_dealer) {
                        $invoice_diff = $vehicle->order?->invoice->invoice_value_from_dealer * -1;
                    }
                @endphp
                <tr>
                    <td>
                        {{ $vehicle->order?->customer?->name() }}
                    </td>
                    @if ( empty( $vehicle->order?->delivery_date) || $vehicle->order?->delivery_date == '0000-00-00 00:00:00')
                        <td></td>
                    @else
                        <td>{{ \Carbon\Carbon::parse($vehicle->order?->delivery_date ?? '')->format( 'd/m/Y' )}}</td>
                    @endif
                    @if ( empty( $vehicle->vehicle_registered_on) || $vehicle->vehicle_registered_on == '0000-00-00 00:00:00')
                        @if( empty( $vehicle->order?->delivery_date) || $vehicle->order?->delivery_date == '0000-00-00 00:00:00')
                        <td></td>
                        @else
                            <td>{{ \Carbon\Carbon::parse($vehicle->order?->delivery_date)->format('F Y') }}</td>
                        @endif
                    @else
                        <td>{{ \Carbon\Carbon::parse($vehicle->vehicle_registered_on)->format( 'F Y' )}}</td>
                    @endif
                    <td>{{ $vehicle->chassis }}</td>
                    <td>{{ $vehicle->reg }}</td>
                    <td>£{{ number_format($vehicle->list_price, 2) }}</td>
                    <td>{{ $vehicle->manufacturer?->name }}</td>
                    <td>{{ $vehicle->model }}</td>
                    <td>{{ $vehicle->derivative }}</td>
                    <td>{{ $vehicle->simplified_type() }}</td>
                    <td>{{ $vehicle->order?->invoice_company->company_name }}</td>
                    <td>{{ $vehicle->order?->dealer->company_name }}</td>
                    @if($vehicle->order)
                        <td>£{{ number_format($vehicle->order->invoiceValue(),2) }}</td>
                    @else
                        <td></td>
                    @endif
                    @if($invoice_diff)
                        <td>£{{ number_format($invoice_diff, 2) }}</td>
                    @else
                        <td></td>
                    @endif
                    @if($vehicle->order?->invoice->commission_to_finance_company)
                        <td>£{{ number_format($vehicle->order?->invoice->commission_to_finance_company,2) }}</td>
                    @else
                        <td></td>
                    @endif
                    @if($vehicle->order?->invoice->invoice_value_to_broker)
                        <td>£{{ number_format($vehicle->order?->invoice->invoice_value_to_broker,2) }}</td>
                    @else
                        <td></td>
                    @endif
                    <td>{{ $vehicle->order?->broker->company_name }}</td>
                    @if($vehicle->order?->invoice->commission_to_broker)
                        <td>£{{ number_format($vehicle->order?->invoice->commission_to_broker,2) }}</td>
                    @else
                        <td></td>
                    @endif
                    <td>
                        £{{ number_format(($invoice_diff + $vehicle->order?->invoice->commission_to_finance_company + $vehicle->order?->invoice->invoice_value_to_broker ) - $vehicle->order?->invoice->commission_to_broker, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="100">No Results Found</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-between">
        @if($vehicles->isNotEmpty())
            <p>Showing {{ $vehicles->firstItem() }} - {{ $vehicles->lastItem() }} of {{ $vehicles->total() }}</p>
        @endif
        <div>{{ $vehicles->links() }}</div>
    </div>
</div>
