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
                                @foreach( $data as $row )
                                    <tr>
                                        <td>{{ $row->id ?? '' }}</td>
                                        <td>{{ $row->vehicle->model ?? ''}}</td>
                                        <td>{{ $row->vehicle->derivative ?? ''}}</td>
                                        <td>{{ $row->order_ref ?? ''}}</td>
                                        <td>{{ $row->vehicle->reg ?? ''}}</td>
                                        <td>{{ \Carbon\Carbon::parse($row->due_date ?? '')->format( 'd/m/Y' )}}</td>
                                        <td>@if ( $row->customer->preffered_name == 'customer')
                                            {{ $row->customer->customer_name ?? ''}}
                                            @else
                                            {{ $row->customer->customer_name ?? ''}}
                                            @endif
                                        </td>
                                        <td>{{ $row->broker_ref ?? ''}}</td>
                                        <td>{{ $row->broker->company_name ?? ''}}</td>
                                        <td>{{ $row->dealer->company_name ?? ''}}</td>
                                        <td>
                                            <a href="/orders/view/{{$row->id}}" class="btn btn-primary"><i class="far fa-eye"></i> View</a>
                                            @can('admin')
                                            <a class="btn btn-primary"><i class="far fa-eye"></i> View</a>
                                            <button type="button" class="btn btn-primary duplicate-order" data-orderNumber="{{ $row->id }}" data-toggle="modal" data-target="#duplicateOrder">
                                                Foo
                                            </button>
                                            @endcan
                                        </td>
                                    </tr>
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

    <!-- Modal -->
    <div class="modal fade" id="duplicateOrder" tabindex="-1" role="dialog" aria-labelledby="duplicateOrder" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Duplicate Order <span id="ModalOrderNumber"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="/orders/duplicate/{{$row->id}}" id="execute-duplication">
                        <input type="number" name="duplicateQty" max="10" id="duplicateQuantity">
                        <button type="submit" class="btn btn-primary ">Go!</button>
                    </form>



                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('custom-scripts')
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/fixedheader/3.1.8/js/dataTables.fixedHeader.min.js"></script>
    <script src="{{ asset('js/dataTables.bootstrap4.min.js') }}"></script>


    <script>
        $(function () {

            $('.duplicate-order').on('click' , function(){
                let orderId = $(this).attr('data-orderNumber');
                let baseURL = '{{env('APP_URL')}}';

                $('#execute-duplication').attr('action', baseURL + '/orders/duplicate/' + orderId);
            });

            var orderQty = $('#duplicateQuantity');

            $(orderQty).change( function (){
                let target = $('.execute-duplication');

                target.attr('href', target.attr('href') );

            });

            $('#dataTable thead tr').clone(true).appendTo( '#dataTable thead' );
            $('#dataTable thead tr:eq(1) th').each( function (i) {
                var title = $(this).text();
                $(this).html( '<input type="text" class="colSearch" data-colName="'+title+'" placeholder="Search '+title+'" />' );
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
            });
        });

    </script>
@endpush
