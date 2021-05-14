@extends('layouts.main', [
    'title' => 'All Notifications',
    'activePage' => 'notifications.index'
    ])

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid force-min-height">

<!-- Content Row -->
<div class="row justify-content-center">

  <!-- Doughnut Chart -->
  <div class="col-lg-10">
    <div class="row">
        <div class="col-md-10">
            <h1 class="h3 mb-4 text-gray-800">All Notifications</h1>
        </div>
        @if (count($all_notifications) > 0)
        <div class="col-md-6 text-right">
            <a href="{{ route('notifications.delete') }}" class="btn btn-sm btn-danger shadow-sm"><i class="fas fa-trash-alt fa-sm text-white-50"></i> Delete Notifications</a>
        </div>
        @endif
    </div>
    @if (count($all_notifications) > 0)
      @foreach ($all_notifications as $notification)
      <a href="{{ route('order.show', $notification->data['order_id']) }}" class="notification">
        <div class="card shadow mb-4">
            <!-- Card Body -->
            <div class="card-body">
                <div class="row">
                    <div class="col-md-1">
                        <div class="icon-circle blue-background">
                        @if ($notification->data['type'] == 'vehicle')
                        <i class="fas fa-car text-white"></i>
                        @else
                        <i class="fas fa-flag text-white"></i>
                        @endif
                        </div>
                    </div>
                    <div class="col-md-11">
                        <div class="small text-gray-500">{{ date('l jS F Y \a\t g:ia', strtotime($notification->created_at)) }}</div>
                        <span class="@if ($notification->read_at == null) font-weight-bold @endif ">{{ $notification->data['message'] }}</span>
                    </div>
                </div>
            </div>
        </div>
      </a>
      @endforeach
    @else
    <div class="card shadow mb-4">
        <!-- Card Body -->
        <div class="card-body">
            You have no notifications yet!
        </div>
    </div>
    @endif
  </div>

</div>

</div>
<!-- /.container-fluid -->

@endsection
