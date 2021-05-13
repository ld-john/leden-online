@extends('layouts.main', [
    'title' => 'Company Management',
    'activePage' => 'user-manager'
    ])

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid force-min-height">

<!-- Content Row -->
<div class="row justify-content-center">

    <div class="col-lg-12">
        <h1 class="h3 mb-4 text-gray-800">Company Management</h1>
        @include('partials.successMsg')
        <div class="card shadow mb-4">
            <!-- Card Header - Dropdown -->
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-l-blue">Companies</h6>
                <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="notificationsDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="notificationsDropdown" x-placement="bottom-end" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(17px, 19px, 0px);">
                        <div class="dropdown-header">Actions</div>
                        <a class="dropdown-item" href="{{ route('company.add') }}">Add New Company</a>
                        <hr>
                        <a class="dropdown-item" href="{{ route('user.add') }}">Add New User</a>
                        <a class="dropdown-item" href="{{ route('user_manager') }}">View User List</a>
                    </div>
                </div>
            </div>
            <!-- Card Body -->
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Company</th>
                                <th>Company Email</th>
                                <th>Company Phone</th>
                                <th>Company Type</th>
                                <th width="15%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach( $data as $row )
                            <tr>
                                <td>{{ $row->company_name ?? '' }}</td>
                                <td>{{ $row->company_email ?? '' }}</td>
                                <td>{{ $row->company_phone ?? '' }}</td>
                                <td>{{ ucfirst($row->company_type) ?? '' }}</td>
                                <td><a href="/companies/edit/{{$row->id}}" class="edit btn btn-warning"><i class="fas fa-edit"></i>Edit</a></td>
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

@endsection

@push('custom-scripts')
    <script src="{{ asset('js/jquery/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/dataTables.bootstrap4.min.js') }}"></script>

    <script>
        $(function () {

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
                fixedHeader: true
            });
        });
    </script>
@endpush
