@extends('layouts.main', [
    'title' => 'Ford Stock and Pipeline',
    'activePage' => 'ford-pipeline'
    ])

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid force-min-height">

<!-- Content Row -->
<div class="row justify-content-center">

    <div class="col-lg-12">
        <h1 class="h3 mb-4 text-gray-800">Ford Stock and Pipeline</h1>
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
                                <th></th>
                                <th>Leden ID</th>
                                <th>Make</th>
                                <th>Model</th>
                                <th>Registration</th>
                                <th>Colour</th>
                                <th>Engine</th>
                                <th>Doors</th>
                                <th>Type</th>
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
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.21/b-1.6.3/sl-1.3.1/datatables.min.css"/>

    <script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.21/b-1.6.3/sl-1.3.1/datatables.min.js"></script>

<script>
$(function () {
    var table = $('#dataTable').DataTable({
        select: {
            style:    'os',
            selector: 'td:first-child'
        },
        columnDefs: [ {
            orderable: false,
            className: 'select-checkbox',
            targets:   0
        } ],
        dom: 'Bfrtip',
        buttons: [
            'selectAll',
            'selectNone',
            {
                text: 'Delete Selected',
                action: function ( e, dt, node, config ) {
                    var count = table.rows( { selected: true } ).count();

                    if(count) {
                        var ids = $.map(table.rows({ selected: true }).data(), function (item) {
                            //console.log(item);
                            return item['id']
                        });

                        $.post('{{ route('pipeline_delete') }}', {"_token": "{{ csrf_token() }}",ids: ids}

                        ).done(function( data ) {
                            table.ajax.reload();
                        });
                        //console.log(ids)
                    }
                }
            }
        ],
        processing: true,
        serverSide: true,
        ajax: "{{ route('pipeline.ford') }}",
        columns: [
            {"data": null, defaultContent: "", className: 'select-checkbox', orderable: false,},
            {data: 'id', name: 'id'},
            {data: 'vehicle_make', name: 'vehicle_make'},
            {data: 'vehicle_model', name: 'vehicle_model'},
            {data: 'vehicle_reg', name: 'vehicle_reg'},
            {data: 'vehicle_colour', name: 'vehicle_colour'},
            {data: 'vehicle_engine', name: 'vehicle_engine'},
            {data: 'vehicle_doors', name: 'vehicle_doors'},
            {data: 'vehicle_type', name: 'vehicle_type'},
            {data: 'action', name: 'action', orderable: false},
        ]
    });
});
</script>
@endpush
