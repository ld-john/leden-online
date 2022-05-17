<!-- Topbar -->
<nav class="navbar navbar-expand navbar-light blue-background topbar mb-4 fixed-top shadow">
    <!-- Sidebar Toggle (Topbar) -->
    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
        <i class="fa fa-bars"></i>
    </button>
    <!-- Topbar Navbar -->
    <ul class="navbar-nav ml-auto align-items-center">
        <!-- Nav Item - Alerts -->
        <li class="nav-item dropdown no-arrow mx-1">
            <a class="nav-link dropdown-toggle text-white" id="alertsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-bell fa-fw"></i>
                <!-- Counter - Alerts -->
                @if (Auth::user()->unreadNotifications()->count() > 9)
                    <span class="badge badge-danger badge-counter">9+</span>
                @elseif (Auth::user()->unreadNotifications()->count() > 0)
                    <span class="badge badge-danger badge-counter">{{ Auth::user()->unreadNotifications()->count() }}</span>
                @endif
            </a>
            <!-- Dropdown - Alerts -->
            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="alertsDropdown">
                <h6 class="dropdown-header">
                    Notifications Center
                </h6>
                @forelse(Auth::user()->unreadNotifications->take(4) as $notification)
                    <div class="dropdown-item d-flex align-items-center text-gray-500">
                        <div class="mr-3">
                            <div class="icon-circle blue-background">
                                @if ($notification->data['type'] == 'vehicle')
                                    <i class="fa-solid fa-car text-white"></i>
                                @else
                                    <i class="fas fa-flag"></i>
                                @endif
                            </div>
                        </div>
                        <div>
                            <div class="small text-gray-500">{{ date('l jS F Y \a\t g:ia', strtotime($notification->created_at)) }}</div>
                            <span class="font-weight-bold">{{ $notification->data['message'] }}</span>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-gray-500 px-4">No Unread Notifications</p>
                @endforelse
                <a class="dropdown-item text-center small text-gray-500" href="{{ route('notifications.read') }}">Mark all as read</a>
                <a class="dropdown-item text-center small text-gray-500" href="{{ route('notifications') }}">View All Notifications</a>
            </div>
        </li>
        <!-- Nav Item - Messages -->
        <li class="nav-item dropdown no-arrow mx-1">
            <a class="nav-link dropdown-toggle text-white" href="#" id="messagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-envelope fa-fw"></i>
                <!-- Counter - Messages -->
                @if (UserMessages::getAllUnreadMessages(Auth::user()->id)->count() > 9)
                    <span class="badge badge-danger badge-counter">9+</span>
                @elseif (UserMessages::getAllUnreadMessages(Auth::user()->id)->count() > 0)
                    <span class="badge badge-danger badge-counter">{{ UserMessages::getAllUnreadMessages(Auth::user()->id)->count() }}</span>
                @endif
            </a>
            <!-- Dropdown - Messages -->
            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="messagesDropdown">
                <h6 class="dropdown-header">
                    Message Center
                </h6>
                @foreach (UserMessages::getUnreadMessages(4, Auth::user()->id) as $new_message)
                    <a class="dropdown-item d-flex align-items-center" href="{{ route('message.view', $new_message->message_group_id) }}">
                        <div class="mr-3">
                            <div class="icon-circle blue-background">
                                <span class="text-white font-weight-bold">{{ substr(ucfirst($new_message->firstname), 0, 1) }}</span>
                            </div>
                        </div>
                        <div>
                            <div class="small text-gray-500">{{ date('l jS F Y \a\t g:ia', strtotime($new_message->created_at)) }}</div>
                            <span class="font-weight-bold">{{ $new_message->subject }}</span>
                        </div>
                    </a>
                @endforeach
                <a class="dropdown-item text-center small text-gray-500" href="{{ route('messages') }}">View More Messages</a>
            </div>
        </li>
        <div class="topbar-divider d-none d-sm-block"></div>
        <!-- Nav Item - User Information -->
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle text-white" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline small">{{ Auth::user()->firstname }} {{ Auth::user()->lastname }}</span>
                <img class="img-profile rounded-circle" src="{{ asset('images/profile.png') }}">
            </a>
            <!-- Dropdown - User Information -->
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <a class="dropdown-item" href="{{ route('profile') }}">
                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                    Profile
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item"href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                    Logout
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </li>
    </ul>
</nav>
<!-- End of Topbar -->
