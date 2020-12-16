@extends('layouts.main', [
    'title' => 'Completed Orders',
    'activePage' => 'completed-orders'
    ])

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid force-min-height">

<!-- Content Row -->
<div class="row justify-content-center">

    <div class="col-lg-12">
        <h1 class="h3 mb-4 text-gray-800">Completed Orders</h1>

        <div class="card shadow mb-4">
            <!-- Card Body -->
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Leden ID</th>
                                <th>Make</th>
                                <th>Model</th>
                                <th>Type</th>
                                <th>Registration</th>
                                <th>Chassis Number</th>
                                <th>Customer</th>
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
        ajax: "{{ route('completed_orders') }}",
        columns: [
            {data: 'id', name: 'id'},
            {data: 'vehicle_make', name: 'vehicle_make'},
            {data: 'vehicle_model', name: 'vehicle_model'},
            {data: 'vehicle_type', name: 'vehicle_type'},
            {data: 'vehicle_reg', name: 'vehicle_reg'},
            {data: 'chassis', name: 'chassis'},
            {data: 'customer', name: 'customer'},
            {data: 'action', name: 'action', orderable: false},
            {data: 'customer_name', name: 'customer_name', visible: false},
            {data: 'company_name', name: 'company_name', visible: false},
        ]
    });
});
</script>
@endpush
