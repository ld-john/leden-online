    <div class="card shadow mb-4">
        <!-- Card Header - Dropdown -->
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-l-blue">Messages</h6>
            <a href="{{ route('messages') }}" class="btn btn-primary">View all messages</a>
        </div>
        <!-- Card Body -->
        <div class="card-body">
            @if (Auth::user()->unreadMessagesCount() > 0)
                @foreach (Auth::user()->recentMessages() as $show_message)
                    <a href="{{ route('messages') }}" class="notification text-dark ">
                        <div class="row border-bottom border-primary py-2">
                            <div class="col-md-12">
                                <small class="small font-weight-bold ">{{ \Carbon\Carbon::parse($show_message->created_at)->format('d M') }}</small>
                                <div class="@if ($show_message->recipient_read_at === null) font-weight-bold @endif ">{{ $show_message->message }}</div>
                                <h6 class="mb-0">{{ $show_message->sender->firstname }} {{ $show_message->sender->lastname }}</h6>
                            </div>
                        </div>
                    </a>
                @endforeach
            @else
                You have no new messages to display
            @endif
        </div>
    </div>

