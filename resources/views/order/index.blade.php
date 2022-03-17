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
                        @livewire('order-table', ['status' => $status])
{{--                        <div class="table-responsive">--}}
{{--                            <table class="table table-bordered" id="dataTable">--}}
{{--                                <thead>--}}
{{--                                <tr>--}}
{{--                                    <th>Leden Order #</th>--}}
{{--                                    <th>Model</th>--}}
{{--                                    <th>Derivative</th>--}}
{{--                                    <th>Ford Order Number</th>--}}
{{--                                    <th>Orbit Number</th>--}}
{{--                                    <th>Registration</th>--}}
{{--                                    <th>Planned Build Date</th>--}}
{{--                                    <th>Due Date</th>--}}
{{--                                    <th>Status</th>--}}
{{--                                    <th>Customer</th>--}}
{{--                                    <th>Broker Order Ref</th>--}}
{{--                                    <th>Broker</th>--}}
{{--                                    <th>Dealership</th>--}}
{{--                                    <th>Action</th>--}}
{{--                                </tr>--}}
{{--                                </thead>--}}
{{--                                <tbody>--}}
{{--                                @foreach( $data as $row )--}}
{{--                                    <tr>--}}
{{--                                        <td>{{ $row->id ?? '' }}</td>--}}
{{--                                        <td>{{ $row->vehicle->model ?? ''}}</td>--}}
{{--                                        <td>{{ $row->vehicle->derivative ?? ''}}</td>--}}
{{--                                        <td>{{ $row->vehicle->ford_order_number ?? ''}}</td>--}}
{{--                                        <td>{{ $row->vehicle->orbit_number }}</td>--}}
{{--                                        <td>{{ $row->vehicle->reg ?? ''}}</td>--}}

{{--                                        @if ( empty( $row->vehicle->build_date) || $row->vehicle->build_date == '0000-00-00 00:00:00')--}}
{{--                                            <td></td>--}}
{{--                                        @else--}}
{{--                                            <td>{{ \Carbon\Carbon::parse($row->vehicle->build_date ?? '')->format( 'd/m/Y' )}}</td>--}}
{{--                                        @endif--}}

{{--                                        @if ( empty( $row->due_date) || $row->due_date == '0000-00-00 00:00:00')--}}
{{--                                            <td></td>--}}
{{--                                        @else--}}
{{--                                            <td>{{ \Carbon\Carbon::parse($row->due_date ?? '')->format( 'd/m/Y' )}}</td>--}}
{{--                                        @endif--}}

{{--                                        <td>{{ $row->vehicle->status() }}</td>--}}

{{--                                        <td>@if ( $row->customer->preffered_name == 'customer')--}}
{{--                                                {{ $row->customer->customer_name ?? ''}}--}}
{{--                                            @else--}}
{{--                                                {{ $row->customer->customer_name ?? ''}}--}}
{{--                                            @endif--}}
{{--                                        </td>--}}
{{--                                        <td>{{ $row->broker_ref ?? ''}}</td>--}}
{{--                                        <td>{{ $row->broker->company_name ?? ''}}</td>--}}
{{--                                        <td>{{ $row->dealer->company_name ?? ''}}</td>--}}
{{--                                        <td width="100px">--}}
{{--                                            <a href="{{route('order.show', $row->id)}}" class="btn btn-primary" data-toggle="tooltip" title="View Order"><i class="far fa-eye"></i></a>--}}
{{--                                            @can('admin')--}}
{{--                                                <a href="{{route('order.edit', $row->id)}}" class="btn btn-warning" data-toggle="tooltip" title="Edit Order"><i class="fas fa-edit"></i></a>--}}
{{--                                                <a data-toggle="tooltip" title="Copy Order">--}}
{{--                                                <button type="button"--}}
{{--                                                        class="btn btn-primary duplicate-order"--}}
{{--                                                        data-orderNumber="{{ $row->id }}"--}}
{{--                                                        data-toggle="modal"--}}
{{--                                                        data-target="#duplicateOrder"--}}
{{--                                                        onclick="duplicateOrder({{$row->id}})">--}}
{{--                                                    <i class="fas fa-copy"></i>--}}
{{--                                                </button>--}}
{{--                                                </a>--}}
{{--                                                <a data-toggle="tooltip" title="Delete Order">--}}
{{--                                                    <livewire:delete-order :order="$row->id" :vehicle="$row->vehicle" />--}}
{{--                                                </a>--}}
{{--                                                <a data-toggle="tooltip" title="Quick Edit">--}}
{{--                                                    <livewire:quick-edit-order :order="$row->id" :vehicle="$row->vehicle" />--}}
{{--                                                </a>--}}
{{--                                            @endcan--}}
{{--                                        </td>--}}
{{--                                    </tr>--}}
{{--                                @endforeach--}}
{{--                                </tbody>--}}
{{--                            </table>--}}
{{--                        </div>--}}
                    </div>
                </div>

            </div>

        </div>

    </div>
    <!-- /.container-fluid -->
    @can('admin')
        <!-- Duplication Modal -->
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
                        @if(isset($row))
                            <form action="/orders/duplicate/{{$row->id}}" id="execute-duplication">
                                <label for="duplicateQuantity">Duplicating Order #<span id="duplication_target"></span> - How Many copies?</label>
                                <input type="number" name="duplicateQty" min="0" max="10" id="duplicateQuantity">
                                <button type="submit" class="btn btn-primary ">Go!</button>
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

            let table = $('#dataTable').DataTable({
                orderCellsTop: true,
            });

            $('#dataTable thead tr').clone(true).appendTo( '#dataTable thead' );
            $('#dataTable thead tr:eq(1) th').each( function (i) {
                let title = $(this).text();
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

        });

        function deleteOrder( id ) {
            let baseURL = '{{env('APP_URL')}}'
            console.log( id + ' targeted for deletion')
            document.querySelector('#execute-deletion').action = baseURL + '/orders/delete/' + id;
            document.querySelector('#deletion_target').textContent = id;
        }

        function duplicateOrder ( id ) {
            let baseURL = '{{env('APP_URL')}}'
            console.log( id + ' targeted for duplication')
            document.querySelector('#execute-duplication').action = baseURL + '/orders/duplicate/' + id;
            document.querySelector('#duplication_target').textContent = id;
        }

        $(document).ready( function() {
            $('.duplicate-order').click( function(){
                let orderId = $(this).attr('data-orderNumber');
                let baseURL = '{{env('APP_URL')}}';
                console.log( orderId + ' Targeted for duplication');
                $('#execute-duplication').attr('action', baseURL + '/orders/duplicate/' + orderId);
            });

            let orderQty = $('#duplicateQuantity');

            $(orderQty).change( function (){
                let target = $('.execute-duplication');

                target.attr('href', target.attr('href') );

            });
        })

    </script>
@endpush
