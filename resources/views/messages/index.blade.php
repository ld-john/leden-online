@extends('layouts.main', [
    'title' => 'Messages',
    'activePage' => 'messages'
    ])

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid force-min-height">

<!-- Content Row -->
<div class="row justify-content-center">

    <div class="col-lg-12">
        <h1 class="h3 mb-4 text-gray-800">Messages</h1>
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
                <h6 class="m-0 font-weight-bold text-l-blue">All Messages</h6>
                <a href="{{ route('message.new') }}" class="btn btn-primary">New Message</a>
            </div>
            <!-- Card Body -->
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th width="40%">Subject</th>
                                <th>Sender</th>
                                <th>Recipient</th>
                                <th>Last message sent</th>
                                <th width="10%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach( $data as $row )
                            <tr>
                                <td>{{ $row->subject ?? '' }}</td>
                                <td>{{ $row->sender ?? '' }}</td>
                                <td>{{ $row->recipient ?? '' }}</td>
                                <td>{{ \Carbon\Carbon::parse($row->last_message_sent)->format('l jS F Y \a\t g:ia')}}</td>
                                <td>
                                    <a href="{{route('message.view', [$row->message_group_id])}}" class="btn btn-primary"><i class="far fa-eye"></i> View</a>
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

        var table = $('#dataTable').DataTable({
            orderCellsTop: true,
        });
    });
</script>
@endpush
