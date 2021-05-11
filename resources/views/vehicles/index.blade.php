@extends('layouts.main', [
    'title' => $title,
    'activePage' => $active_page
    ])

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid force-min-height">

        <!-- Content Row -->
        <div class="row justify-content-center">

            <div class="col-lg-12">
                <h1 class="h3 mb-4 text-gray-800">{{$title}}</h1>
                @include('partials.successMsg')

                <div class="card shadow mb-4">
                    <!-- Card Body -->
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th>Leden ID</th>
                                    <th>Make</th>
                                    <th>Model</th>
                                    <th>Derivative</th>
                                    <th>Registration</th>
                                    <th>Engine</th>
                                    <th>Doors</th>
                                    <th>Colour</th>
                                    <th>How many options</th>
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
                            let count = table.rows({selected: true}).count();
                            if(count) {
                                let ids = $.map(table.rows({selected: true}).data(), function (item) {
                                    return item['id']
                                });

                                $.post('{{ route('pipeline_delete') }}', {"_token": "{{ csrf_token() }}",ids: ids}

                                ).done(function( data ) {
                                    table.ajax.reload();
                                });
                            }
                        }
                    },
                ],
                processing: true,
                serverSide: true,
                ajax: "{{ route($route) }}",
                columns: [
                    {"data": null, defaultContent: "", className: 'select-checkbox', orderable: false,},
                    {data: 'id', name: 'id'},
                    {data: 'manufacturer.name', name: 'manufacturer.name'},
                    {data: 'model', name: 'model'},
                    {data: 'derivative', name: 'derivative'},
                    {data: 'reg', name: 'reg'},
                    {data: 'engine', name: 'engine'},
                    {data: 'doors', name: 'doors'},
                    {data: 'colour', name: 'colour'},
                    {data: 'options', name: 'options'},
                    {data: 'type', name: 'type'},
                    {data: 'action', name: 'action', orderable: false},
                ],
                order: [
                    [1, 'asc']
                ]
            });
        });
    </script>
@endpush
