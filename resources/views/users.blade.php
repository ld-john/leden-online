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
                                <th>Firstname</th>
                                <th>Lastname</th>
                                <th>Email</th>
                                <th>Company</th>
                                <th>Role</th>
                                <th width="15%">Action</th>
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
        ajax: "{{ route('user_manager') }}",
        columns: [
            {data: 'firstname', name: 'firstname'},
            {data: 'lastname', name: 'lastname'},
            {data: 'email', name: 'email'},
            {data: 'company', name: 'company'},
            {data: 'role', name: 'role'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });
});
</script>
@endpush