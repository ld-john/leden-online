<div class="col-lg-6">
    <div class="card shadow mb-4 h-300">
        <!-- Card Header - Dropdown -->
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between h-75p">
            <h6 class="m-0 font-weight-bold text-l-blue">Notifications</h6>
            <a href="{{ route('notifications') }}" class="btn btn-primary">View all notifications</a>
        </div>
        <!-- Card Body -->
        <div class="card-body">
            @livewire('notification.notification-panel')
        </div>
    </div>
</div>
