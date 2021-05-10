@extends('layouts.main', [
    'title' => 'Order Bank',
    'activePage' => 'order-bank'
    ])

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid force-min-height">

        <!-- Content Row -->
        <div class="row justify-content-center">

            <div class="col-lg-12">
                <h1 class="h3 mb-4 text-gray-800">Order Bank</h1>
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

                <div class="card shadow mb-4">
                    <!-- Card Body -->
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                <tr>
                                    <th>Leden ID</th>
                                    <th>Model</th>
                                    <th>Derivative</th>
                                    <th>Order Number</th>
                                    <th>Registration</th>
                                    <th>Due Date</th>
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
                ajax: "{{ route('order_bank') }}",
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'vehicle_model', name: 'vehicle_model'},
                    {data: 'vehicle_derivative', name: 'vehicle_derivative'},
                    {data: 'order_ref', name: 'order_ref'},
                    {data: 'vehicle_reg', name: 'vehicle_reg'},
                    {data: 'vehicle_due_date', name: 'vehicle_due_date'},
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
