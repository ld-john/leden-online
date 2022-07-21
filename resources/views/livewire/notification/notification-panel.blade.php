<div>
    @forelse($notifications as $notification)
        <div class="notification">
            <div class="row mb-2">
                <div class="col-md-1">
                    <div class="icon-circle blue-background text-white">
                        @if ($notification->data['type'] == 'vehicle')
                            <i class="fa-solid fa-car"></i>
                        @else
                            <i class="fa-solid fa-flag"></i>
                        @endif
                    </div>
                </div>
                <div class="col-md-11">
                    <div class="small text-gray-500">{{ date('l jS F Y \a\t g:ia', strtotime($notification->created_at)) }}</div>
                    <span class="@if ($notification->read_at == null) font-weight-bold @endif ">{{ $notification->data['message'] }}</span>
                </div>
            </div>
        </div>
        <hr>
    @empty
        You have no notifications to display
    @endforelse
</div>
