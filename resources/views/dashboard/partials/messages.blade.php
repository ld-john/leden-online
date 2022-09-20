    <div class="card shadow mb-4">
        <!-- Card Header - Dropdown -->
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-l-blue">Messages</h6>
            <a href="{{ route('messages') }}" class="btn btn-primary">View all messages</a>
        </div>
        <!-- Card Body -->
        <div class="card-body p-0">
            <ul class="list-group list-group-flush">
                @forelse(Auth::user()->recentMessages() as $message)
                    <li class="list-group-item">
                        <a href="{{ route('messages') }}" class="notification text-dark ">
                            <div class="row align-items-center">
                                <div class="col-md-1">
                                    @if($message->sender->avatar)
                                        <img class="img-profile rounded-circle mx-1" src="{{ asset( $message->sender->avatar ) }}" alt="profile">
                                    @else
                                        <img class="img-profile rounded-circle mx-1" src="{{ asset('images/profile.png') }}" alt="profile">
                                    @endif
                                </div>
                                <div class="col-md-11">
                                    <small class="small text-gray-500 ">{{ \Carbon\Carbon::parse($message->created_at)->format('l jS F Y \a\t g:ia') }}</small>
                                    <div class="@if ($message->recipient_read_at !== null) font-weight-bold @endif ">{{ $message->message }}</div>
                                    <h6 class="mb-0">{{ $message->sender->firstname }} {{ $message->sender->lastname }}</h6>
                                </div>
                            </div>
                        </a>
                    </li>
                @empty
                    <li class="list-group-item">You have no new messages to display</li>
                @endforelse
            </ul>
        </div>
    </div>

