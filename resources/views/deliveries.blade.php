@extends('layouts.main', [
    'title' => 'Manage Deliveries',
    'activePage' => 'deliveries'
    ])

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid force-min-height">

<!-- Content Row -->
<div class="row justify-content-center">

    <div class="col-lg-12">
        <h1 class="h3 mb-4 text-gray-800">Manage Deliveries</h1>

        <div class="card shadow mb-4">
            <!-- Card Body -->
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Leden ID</th>
                                <th>Model</th>
                                <th>Status of Delivery</th>
                                <th>Order Number</th>
                                <th>Registration</th>
                                <th>Delivery Date</th>
                                <th>Customer</th>
                                <th>Broker Order Ref</th>
                                <th>Broker</th>
                                <th>Dealership</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

</div>

</div>
<!-- /.container-fluid -->

@endsection

@push('custom-scripts')
<script src="{{ asset('js/jquery/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('js/dataTables.bootstrap4.min.js') }}"></script>

<script>
$(function () {
    var table = $('#dataTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('manage_deliveries') }}",
        columns: [
            {data: 'id', name: 'id'},
            {data: 'vehicle_model', name: 'vehicle_model'},
            {data: 'vehicle_status', name: 'vehicle_status'},
            {data: 'order_ref', name: 'order_ref'},
            {data: 'vehicle_reg', name: 'vehicle_reg'},
            {data: 'delivery_date', name: 'delivery_date'},
            {data: 'customer', name: 'customer'},
            {data: 'broker_order_ref', name: 'broker_order_ref'},
            {data: 'broker_name', name: 'broker'},
            {data: 'dealer_name', name: 'dealership'},
            {data: 'action', name: 'action', orderable: false},
            {data: 'customer_name', name: 'customer_name', visible: false},
            {data: 'company_name', name: 'company_name', visible: false},
        ]
    });
});
</script>
@endpush
