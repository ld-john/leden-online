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
                                    <th>ID</th>
                                    <th>Make</th>
                                    <th>Model</th>
                                    <th>Ford Order Number</th>
                                    <th>Derivative</th>
                                    <th>Engine</th>
                                    <th>Colour</th>
                                    <th>Type</th>
                                    <th>Chassis</th>
                                    <th>Registration</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach( $data as $row )
                                    @if(! $row->order()->exists())
                                        <tr>
                                            <td></td>
                                            <td>
                                                <strong>{{ $row->id ?? '' }}</strong>
                                                @if($row->orbit_number)
                                                    <br>(Orbit Number: {{ $row->orbit_number }})
                                                @endif
                                            </td>
                                            <td>{{ $row->manufacturer->name ?? '' }}</td>
                                            <td>{{ $row->model ?? '' }}</td>
                                            <td>{{ $row->ford_order_number ?? '' }}</td>
                                            <td>{{ $row->derivative ?? '' }}</td>
                                            <td>{{ $row->engine ?? '' }}</td>
                                            <td>{{ $row->colour ?? '' }}</td>
                                            <td>{{ $row->type ?? '' }}</td>
                                            <td>{{ $row->chassis ?? '' }}</td>
                                            <td>{{ $row->reg ?? '' }}</td>
                                            <td>{{ $row->status() }}</td>
                                            <td width="100px">
                                                <a href="{{route('vehicle.show', $row->id)}}" class="btn btn-primary" data-toggle="tooltip" title="View Vehicle Information"><i class="far fa-eye"></i></a>
                                                @can('admin')
                                                    <a href="{{route('edit_vehicle', $row->id)}}" class="btn btn-warning" data-toggle="tooltip" title="Edit Vehicle Information"><i class="fas fa-edit"></i></a>
                                                    <a href="{{route('order.reserve', $row->id)}}" class="btn btn-primary" data-toggle="tooltip" title="Create order with Vehicle"><i class="fas fa-plus-square"></i></a>
                                                    <a data-toggle="tooltip" title="Delete Vehicle">
                                                        <livewire:delete-vehicle :vehicle="$row->id" />
                                                    </a>
                                                @endcan
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>
    <!-- /.container-fluid -->

    <style>

        table.dataTable tbody>tr.selected,table.dataTable tbody>tr>.selected{background-color:#b0bed9}table.dataTable.stripe tbody>tr.odd.selected,table.dataTable.stripe tbody>tr.odd>.selected,table.dataTable.display tbody>tr.odd.selected,table.dataTable.display tbody>tr.odd>.selected{background-color:#acbad4}table.dataTable.hover tbody>tr.selected:hover,table.dataTable.hover tbody>tr>.selected:hover,table.dataTable.display tbody>tr.selected:hover,table.dataTable.display tbody>tr>.selected:hover{background-color:#aab7d1}table.dataTable.order-column tbody>tr.selected>.sorting_1,table.dataTable.order-column tbody>tr.selected>.sorting_2,table.dataTable.order-column tbody>tr.selected>.sorting_3,table.dataTable.order-column tbody>tr>.selected,table.dataTable.display tbody>tr.selected>.sorting_1,table.dataTable.display tbody>tr.selected>.sorting_2,table.dataTable.display tbody>tr.selected>.sorting_3,table.dataTable.display tbody>tr>.selected{background-color:#acbad5}table.dataTable.display tbody>tr.odd.selected>.sorting_1,table.dataTable.order-column.stripe tbody>tr.odd.selected>.sorting_1{background-color:#a6b4cd}table.dataTable.display tbody>tr.odd.selected>.sorting_2,table.dataTable.order-column.stripe tbody>tr.odd.selected>.sorting_2{background-color:#a8b5cf}table.dataTable.display tbody>tr.odd.selected>.sorting_3,table.dataTable.order-column.stripe tbody>tr.odd.selected>.sorting_3{background-color:#a9b7d1}table.dataTable.display tbody>tr.even.selected>.sorting_1,table.dataTable.order-column.stripe tbody>tr.even.selected>.sorting_1{background-color:#acbad5}table.dataTable.display tbody>tr.even.selected>.sorting_2,table.dataTable.order-column.stripe tbody>tr.even.selected>.sorting_2{background-color:#aebcd6}table.dataTable.display tbody>tr.even.selected>.sorting_3,table.dataTable.order-column.stripe tbody>tr.even.selected>.sorting_3{background-color:#afbdd8}table.dataTable.display tbody>tr.odd>.selected,table.dataTable.order-column.stripe tbody>tr.odd>.selected{background-color:#a6b4cd}table.dataTable.display tbody>tr.even>.selected,table.dataTable.order-column.stripe tbody>tr.even>.selected{background-color:#acbad5}table.dataTable.display tbody>tr.selected:hover>.sorting_1,table.dataTable.order-column.hover tbody>tr.selected:hover>.sorting_1{background-color:#a2aec7}table.dataTable.display tbody>tr.selected:hover>.sorting_2,table.dataTable.order-column.hover tbody>tr.selected:hover>.sorting_2{background-color:#a3b0c9}table.dataTable.display tbody>tr.selected:hover>.sorting_3,table.dataTable.order-column.hover tbody>tr.selected:hover>.sorting_3{background-color:#a5b2cb}table.dataTable.display tbody>tr:hover>.selected,table.dataTable.display tbody>tr>.selected:hover,table.dataTable.order-column.hover tbody>tr:hover>.selected,table.dataTable.order-column.hover tbody>tr>.selected:hover{background-color:#a2aec7}table.dataTable tbody td.select-checkbox,table.dataTable tbody th.select-checkbox{position:relative}table.dataTable tbody td.select-checkbox:before,table.dataTable tbody td.select-checkbox:after,table.dataTable tbody th.select-checkbox:before,table.dataTable tbody th.select-checkbox:after{display:block;position:absolute;top:1.2em;left:50%;width:12px;height:12px;box-sizing:border-box}table.dataTable tbody td.select-checkbox:before,table.dataTable tbody th.select-checkbox:before{content:" ";margin-top:-6px;margin-left:-6px;border:1px solid black;border-radius:3px}table.dataTable tr.selected td.select-checkbox:after,table.dataTable tr.selected th.select-checkbox:after{content:"✓";font-size:20px;margin-top:-19px;margin-left:-6px;text-align:center;text-shadow:1px 1px #b0bed9,-1px -1px #b0bed9,1px -1px #b0bed9,-1px 1px #b0bed9}table.dataTable.compact tbody td.select-checkbox:before,table.dataTable.compact tbody th.select-checkbox:before{margin-top:-12px}table.dataTable.compact tr.selected td.select-checkbox:after,table.dataTable.compact tr.selected th.select-checkbox:after{margin-top:-16px}div.dataTables_wrapper span.select-info,div.dataTables_wrapper span.select-item{margin-left:.5em}@media screen and (max-width: 640px){div.dataTables_wrapper span.select-info,div.dataTables_wrapper span.select-item{margin-left:0;display:block}}

        []

    </style>

@endsection

@push('custom-scripts')
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/fixedheader/3.1.8/js/dataTables.fixedHeader.min.js"></script>
    <script src="https://cdn.datatables.net/select/1.3.3/js/dataTables.select.min.js"></script>
    <script src="{{ asset('js/dataTables.bootstrap4.min.js') }}"></script>



    <script>
        $(function () {

            $('#dataTable thead tr').clone(true).appendTo( '#dataTable thead' );
            $('#dataTable thead tr:eq(1) th').each( function (i) {
                var title = $(this).text();

                if ( title === '') {
                    title = 'noTitle';
                }
                $(this).html( '<input type="text" class="colSearch" data-colName="'+title+'" placeholder="&#x1f50d; '+title+'" />' );
                $(this).parent().addClass('searchContainer');
                $( 'input', this ).on( 'keyup change', function () {
                    if ( table.column(i).search() !== this.value ) {
                        table
                            .column(i)
                            .search( this.value )
                            .draw();
                    }
                } );
            } );

            var table = $('#dataTable').DataTable({
                orderCellsTop: true,
                columnDefs: [ {
                    orderable: false,
                    className: 'select-checkbox',
                    targets:   0
                } ],
                select: {
                    style:    'os',
                    selector: 'td:first-child'
                },
                order: [[ 1, 'asc' ]]
            } );
        });

    </script>
@endpush
