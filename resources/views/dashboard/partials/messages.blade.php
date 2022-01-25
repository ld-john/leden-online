    <div class="card shadow mb-4">
        <!-- Card Header - Dropdown -->
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-l-blue">Messages</h6>
            <a href="{{ route('messages') }}" class="btn btn-primary">View all messages</a>
        </div>
        <!-- Card Body -->
        <div class="card-body">
            @if (UserMessages::getAllUnreadMessages(Auth::user()->id)->count() > 0)
                @foreach (UserMessages::getUnreadMessages(6, Auth::user()->id) as $show_message)
                    <a href="{{ route('message.view', $show_message->message_group_id) }}" class="notification">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="small text-gray-500">{{ date('l jS F Y \a\t g:ia', strtotime($show_message->created_at)) }}</div>
                                <span class="@if ($show_message->read_at == null) font-weight-bold @endif ">{{ $show_message->subject }}</span>
                            </div>
                        </div>
                    </a>
                @endforeach
            @else
                You have no new messages to display
            @endif
        </div>
    </div>

