<div class="col-lg-6">
    <div class="card shadow mb-4 h-300">
        <!-- Card Header - Dropdown -->
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between h-75p">
            <h6 class="m-0 font-weight-bold text-l-blue">Notifications</h6>
            <a href="{{ route('notifications') }}" class="btn btn-primary">View all notifications</a>
        </div>
        <!-- Card Body -->
        <div class="card-body">
            @if (count($notifications) > 0)
                @foreach ($notifications as $notification)
                    <div class="notification">
                        <div class="row mb-2">
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
                @endforeach
            @else
                You have no notifications to display
            @endif
        </div>
    </div>
</div>
