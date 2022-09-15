@extends('layouts.main', [
    'title' => 'All Notifications',
    'activePage' => 'notifications.index'
    ])

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid force-min-height">

        <!-- Content Row -->
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="row">
                    <div class="col-md-12">
                        <h1 class="h3 mb-4 text-gray-800">All Notifications</h1>
                    </div>
                    @if (count($all_notifications) > 0)
                        <div class="col-md-12 justify-content-end d-flex mb-2">
                            <a class="btn btn-sm btn-warning shadow-sm" href="{{ route('notifications.read') }}"><i class="fa-solid fa-eye"></i> Mark all as read</a>
                            <a href="{{ route('notifications.delete') }}" class="ms-2 btn btn-sm btn-danger shadow-sm"><i class="fa-solid fa-trash-can fa-sm text-white-50"></i> Delete Notifications</a>
                        </div>
                    @endif
                </div>
                @forelse($all_notifications as $notification)
                    <div class="notification">
                        <div class="card @if ($notification->read_at == null) bg-info text-white @endif shadow mb-4">
                            <!-- Card Body -->
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-1">
                                        <div class="icon-circle blue-background">
                                            @if ($notification->data['type'] == 'vehicle')
                                                <i class="fa-solid fa-car text-white"></i></i>
                                            @else
                                                <i class="fa-solid fa-flag text-white"></i>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-11">
                                        <div class="small text-gray-500">{{ date('l jS F Y \a\t g:ia', strtotime($notification->created_at)) }}</div>
                                        <div class="@if ($notification->read_at == null) text-white font-weight-bold @endif ">{{ $notification->data['message'] }}</a></div>
                                        @if ($notification->read_at == null)
                                            <a href="{{ route('notifications.mark-read', $notification->id) }}" class="text-white">Mark as read </a>
                                        @else
                                            <a href="{{ route('notifications.mark-unread', $notification->id) }}">Mark as unread</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="card shadow mb-4">
                        <!-- Card Body -->
                        <div class="card-body">
                            You have no notifications yet!
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
        <div class="d-flex justify-content-center">
            {{ $all_notifications->links() }}
        </div>
    </div>
    <!-- /.container-fluid -->

@endsection
