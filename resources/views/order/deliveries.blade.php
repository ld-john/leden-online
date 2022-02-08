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
                                    <th>ID</th>
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
                                @foreach( $data as $row )
                                    <tr>
                                        <td>{{ $row->id ?? '' }}</td>
                                        <td>{{ $row->vehicle->model ?? ''}}</td>
                                        <td>
                                            @switch ($row->vehicle->vehicle_status)
                                                @case(3)
                                                Ready for delivery
                                                @break
                                                @case(4)
                                                Factory Order
                                                @break
                                                @case(6)
                                                Delivery Booked
                                            @endswitch
                                        </td>
                                        <td>{{ $row->order_ref ?? ''}}</td>
                                        <td>{{ $row->vehicle->reg ?? ''}}</td>
                                        <td>
                                            @if( isset ($row->delivery_date) && ( $row->delivery_date != '0000-00-00 00:00:00') )
                                            {{ \Carbon\Carbon::parse($row->delivery_date)->format( 'd/m/Y' )}}
                                            @else
                                            TBD
                                            @endif
                                        </td>
                                        <td>@if ( $row->customer->preffered_name == 'customer')
                                                {{ $row->customer->customer_name ?? ''}}
                                            @else
                                                {{ $row->customer->customer_name ?? ''}}
                                            @endif
                                        </td>
                                        <td>{{ $row->broker_ref ?? ''}}</td>
                                        <td>{{ $row->broker->company_name ?? ''}}</td>
                                        <td>{{ $row->dealer->company_name ?? ''}}</td>
                                        <td width="100px">
                                            <a href="/orders/view/{{$row->id}}" class="btn is-full btn-primary" data-toggle="tooltip" title="View Order"><i class="far fa-eye"></i></a>
                                            @can('admin')
                                                <a data-toggle="tooltip" title="Delete Order">
                                                    <livewire:delete-order :order="$row->id" :vehicle="$row->vehicle" />
                                                </a>
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
    @can('admin')
        <!-- Delete Modal -->
        <div class="modal fade" id="deleteOrder" tabindex="-1" role="dialog" aria-labelledby="deleteOrder" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Delete Order <span id="ModalOrderNumber"></span></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @if(isset($row))
                            <form action="/orders/delete/{{$row->id}}" id="execute-deletion">
                                <label>Are you sure you want to delete Order # <span id="deletion_target"></span></label>
                                <br>
                                <button type="submit" class="btn btn-danger ">Go!</button>
                            </form>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endcan

@endsection

@push('custom-scripts')
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/fixedheader/3.1.8/js/dataTables.fixedHeader.min.js"></script>
    <script src="{{ asset('js/dataTables.bootstrap4.min.js') }}"></script>


    <script>
        $(function () {

            $('.delete-order').on('click' , function(){
                let orderId = $(this).attr('data-orderNumber');
                let baseURL = '{{env('APP_URL')}}';

                $('#execute-deletion').attr('action', baseURL + '/orders/delete/' + orderId);
                $('#deletion_target').text(orderId);
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
