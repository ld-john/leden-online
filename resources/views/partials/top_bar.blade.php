<!-- Topbar -->
<nav class="navbar navbar-expand navbar-light blue-background topbar mb-4 fixed-top shadow">
    <div class="container-fluid">
        <!-- Sidebar Toggle (Topbar) -->
        <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle me-3">
            <i class="fa fa-bars"></i>
        </button>
        <!-- Topbar Navbar -->
        <ul class="navbar-nav ms-auto align-items-center">
            <!-- Nav Item - Alerts -->
            @livewire('notification.notifications-popup')
            <!-- Nav Item - Messages -->
            <li class="nav-item dropdown no-arrow mx-2 ">
                <button
                        type="button"
                        class="btn btn-outline-primary text-white position-relative nav-link dropdown-toggle"
                        id="messagesDropdown"
                        role="button"
                        data-toggle="dropdown"
                        aria-haspopup="true"
                        aria-expanded="false"
                >
                    <i class="fas fa-envelope fa-fw"></i>
                    @if(Auth::user()->unreadMessagesCount() > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        @if (Auth::user()->unreadMessagesCount() > 9)
                                9+
                            @elseif (Auth::user()->unreadMessagesCount() > 0)
                                {{ Auth::user()->unreadMessagesCount() }}
                            @endif
                        <span class="visually-hidden">unread messages</span>
                    </span>
                    @endif
                </button>
                <!-- Dropdown - Messages -->
                <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="messagesDropdown">
                    <h6 class="dropdown-header">
                        Message Center
                    </h6>
                    @foreach (Auth::user()->recentMessages() as $message)
                        <a class="dropdown-item" href="{{ route('messages') }}">
                            <small class="small font-weight-bold">{{ \Carbon\Carbon::parse($message->created_at)->format('d M') }}</small>
                            <span class="clearfix"></span>
                            <div class="@if ($message->recipient_read_at === null) font-weight-bold @endif font-italic mb-0 text-small">{{ $message->message }}</div>
                            <h6 class="mb-0">{{ $message->sender->firstname }} {{ $message->sender->lastname }}</h6>
                        </a>
                    @endforeach
                    <a class="dropdown-item text-center small text-gray-500" href="{{ route('messages') }}">View More Messages</a>
                </div>
            </li>
            <div class="topbar-divider d-none d-sm-block"></div>
            <!-- Nav Item - User Information -->
            <li class="nav-item dropdown no-arrow">
                <a class="nav-link dropdown-toggle d-flex align-items-center btn btn-outline-primary text-white" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="me-2 small">{{ Auth::user()->firstname }} {{ Auth::user()->lastname }}</span>
                    <img class="img-profile rounded-circle mx-1" src="{{ asset('images/profile.png') }}" alt="profile">
                </a>
                <!-- Dropdown - User Information -->
                <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                    <a class="dropdown-item" href="{{ route('profile') }}">
                        <i class="fas fa-user fa-sm fa-fw me-2 text-gray-400"></i>
                        Profile
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt fa-sm fa-fw me-2 text-gray-400"></i>
                        Logout
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </li>
        </ul>
    </div>
</nav>
<!-- End of Topbar -->
