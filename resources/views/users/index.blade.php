@extends('layouts.main', [
    'title' => 'User Management',
    'activePage' => 'user-manager'
    ])

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid force-min-height">

<!-- Content Row -->
<div class="row justify-content-center">

    <div class="col-lg-12">
        <h1 class="h3 mb-4 text-gray-800">User Management</h1>
        @include('partials.successMsg')
        <div class="card shadow mb-4">
            <!-- Card Header - Dropdown -->
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-l-blue">Users</h6>
                <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="notificationsDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="notificationsDropdown" x-placement="bottom-end" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(17px, 19px, 0px);">
                        <div class="dropdown-header">Actions</div>
                        <a class="dropdown-item" href="{{ route('user.add') }}">Add New User</a>
                        <hr>
                        <a class="dropdown-item" href="{{ route('company.add') }}">Add New Company</a>
                        <a class="dropdown-item" href="{{ route('company_manager') }}">View Company List</a>
                    </div>
                </div>
            </div>
            <!-- Card Body -->
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Leden ID</th>
                                <th>First name</th>
                                <th>Last name</th>
                                <th>Email</th>
                                <th>Company</th>
                                <th>Role</th>
                                <th width="12%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach( $data as $row )
                            <tr>
                                <td>{{ $row->id ?? '' }}</td>
                                <td>{{ $row->firstname ?? '' }}</td>
                                <td>{{ $row->lastname ?? '' }}</td>
                                <td>{{ $row->email ?? '' }}</td>
                                <td>{{ $row->company->company_name ?? '' }}</td>
                                <td>{{ ucfirst($row->role) ?? '' }}</td>
                                <td class="btn-flex">
                                    @if($row->is_deleted === null)
                                        <a href="/user-management/edit/{{$row->id}}" class="btn btn-warning" data-toggle="tooltip" title="Edit Profile"><i class="fas fa-edit"></i></a>
                                        <a href="/user-management/disable/{{$row->id}}" class="btn btn-danger" data-toggle="tooltip" title="Disable Profile"><i class="fas fa-times"></i></a>
                                    @else
                                        <a href="/user-management/disable/{{$row->id}}" class="btn btn-success" data-toggle="tooltip" title="Enable Profile"><i class="fas fa-check"></i></a>
                                        <a href="/user-management/delete/{{$row->id}}" class="btn btn-danger" data-toggle="tooltip" title="Delete Profile"><i class="fas fa-trash"></i></a>
                                    @endif
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
        });
    });
</script>
@endpush
