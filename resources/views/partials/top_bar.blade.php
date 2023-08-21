<!-- Topbar -->
<nav class="navbar navbar-expand navbar-light blue-background topbar shadow">
    <div class="container-fluid">
        <!-- Topbar Navbar -->
        <a class="navbar-brand" href="/">
            <img src="{{ asset('images/leden-group-ltd-white.png') }}" alt="Leden Logo" width="250px">
        </a>
        <ul class="navbar-nav align-items-center">
            <!-- Nav Item - Alerts -->
            @livewire('notification.notifications-popup')
            <!-- Nav Item - Messages -->
            <li class="nav-item dropdown me-3">
                <a class="nav-link dropdown-toggle btn btn-outline-primary text-white position-relative nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
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
                </a>
                <ul class="dropdown-menu" style="left: -200px" aria-labelledby="navbarDropdown">
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
                    <li class="dropdown-divider"></li>
                    <a class="dropdown-item text-center small text-gray-500" href="{{ route('messages') }}">View More Messages</a>
                </ul>
            </li>
            <!-- Nav Item - User Information -->
                <!-- Dropdown - User Information -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle d-flex align-items-center btn btn-outline-primary text-white" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <span class="me-2 small">{{ Auth::user()->firstname }} {{ Auth::user()->lastname }}</span>
                    @if(Auth::user()->avatar)
                        <img class="img-profile rounded-circle mx-1" src="{{ asset( Auth::user()->avatar ) }}" alt="profile">
                    @else
                        <img class="img-profile rounded-circle mx-1" src="{{ asset('images/profile.png') }}" alt="profile">
                    @endif
                </a>
                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <li>
                        <a class="dropdown-item" href="{{ route('profile') }}">
                            <i class="fas fa-user fa-sm fa-fw me-2 text-gray-400"></i>
                            Profile
                        </a>
                    </li>
                    <div class="dropdown-divider"></div>
                    <li>
                        <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt fa-sm fa-fw me-2 text-gray-400"></i>
                            Logout
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>
<nav class="navbar navbar-light navbar-expand shadow bg-light mb-4">
    <div class="container-fluid justify-content-center">
        <ul class="navbar-nav">
            <li class="nav-item @if ($activePage == 'dashboard') active @endif">
                <a class="nav-link" href="{{ route('dashboard') }}">
                    <i class="fa-solid fa-gauge-high"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            @can('admin')
                <li class="nav-item @if ($activePage == 'create-order') active @endif">
                    <a class="nav-link" href="{{ route('create_order') }}">
                        <i class="fa-solid fa-plus"></i>
                        <span>Create Order</span>
                    </a>
                </li>
            @endcan
            @can('admin')
                <li class="nav-item @if ($activePage == 'create-vehicle') active @endif">
                    <a class="nav-link" href="{{ route('create_vehicle') }}">
                        <i class="fa-solid fa-plus"></i>
                        <span>Create Vehicle</span>
                    </a>
                </li>
            @endcan
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <span>Orders</span>
                </a>
                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <li>
                        <a class="dropdown-item" href="{{ route('reservation.index') }}">
                            <i class="fa-solid fa-bookmark"></i>
                            <span>Reservations</span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('order_bank') }}">
                            <i class="fa-solid fa-piggy-bank"></i>
                            <span>Order Bank</span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('manage_deliveries') }}">
                            <i class="fa-solid fa-truck"></i>
                            <span>Manage Deliveries</span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('completed_orders') }}">
                            <i class="fa-solid fa-clipboard-check"></i>
                            <span>Completed Orders</span>
                        </a>
                    </li>
                </ul>
            </li>
            @can('admin')
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <span>Stock</span>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li>
                            <a class="dropdown-item" href="{{ route('pipeline') }}">
                                <i class="fa-solid fa-clipboard-list"></i>
                                <span>Leden Stock</span>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('ring_fenced_stock') }}">
                                <i class="fa-solid fa-clipboard-list"></i>
                                <span>Ring fenced Stock</span>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('pipeline.ford') }}">
                                <i class="fa-solid fa-clipboard-list"></i>
                                <span>Ford Stock and Pipeline</span>
                            </a>
                        </li>
                    </ul>
                </li>
            @endcan
            @can('broker')
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <span>Stock</span>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li>
                            <a class="dropdown-item" href="{{ route('pipeline') }}">
                                <i class="fa-solid fa-clipboard-list"></i>
                                <span>Leden Stock</span>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('ring_fenced_stock') }}">
                                <i class="fa-solid fa-clipboard-list"></i>
                                <span>Ring fenced Stock</span>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('pipeline.ford') }}">
                                <i class="fa-solid fa-clipboard-list"></i>
                                <span>Ford Stock and Pipeline</span>
                            </a>
                        </li>
                    </ul>
                </li>
            @endcan
            @can('admin')
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Uploads
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li>
                            <a class="dropdown-item" href="{{ route('csv_upload') }}">
                                <i class="fa-solid fa-file-csv"></i>
                                <span>CSV Upload</span>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('locations.create') }}">
                                <i class="fa-solid fa-location-pin"></i>
                                <span>Manage Ford Report Locations</span>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('rf_upload') }}">
                                <i class="fa-solid fa-file-csv"></i>
                                <span>Ring Fenced Stock CSV Upload</span>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('fit_option_upload') }}">
                                <i class="fa-solid fa-file-csv"></i>
                                <span>Fit Options CSV Upload</span>
                            </a>
                        </li>
                    </ul>
                </li>
            @endcan
            @can('admin')
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Reporting
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li>
                            <a class="dropdown-item" href="{{ route('vehicle.search') }}">
                                <i class="fa-solid fa-magnifying-glass"></i>
                                <span>Universal Search</span>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('reporting') }}">
                                <i class="fa-solid fa-chart-bar"></i>
                                <span>Report/Track Status</span>
                            </a>
                        </li>
                    </ul>
                </li>
            @endcan
            <li class="nav-item @if ($activePage == 'messages') active @endif">
                <a class="nav-link" href="{{ route('messages') }}">
                    <i class="fa-solid fa-envelope"></i>
                    <span>Messages</span>
                </a>
            </li>
            @can('admin')
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Listings
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li>
                            <a class="dropdown-item" href="{{ route('user_manager') }}">
                                <i class="fa-solid fa-users"></i>
                                <span>User Listing</span>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('company_manager') }}">
                                <i class="fa-solid fa-users"></i>
                                <span>Company Listings</span>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('customer.index') }}">
                                <i class="fa-solid fa-user-group"></i>
                                <span>Customers</span>
                            </a>
                        </li>
                    </ul>
                </li>
            @endcan
            @can('admin')
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Recycle Bin
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="{{ route('vehicle.recycle_bin') }}"><i class="fa-solid fa-trash"></i>
                                <span>Vehicle Recycle Bin</span></a></li>
                        <li><a class="dropdown-item" href="{{ route('order.recycle-bin') }}"><i class="fa-solid fa-trash"></i>
                                <span>Orders Recycle Bin</span></a></li>
                    </ul>
                </li>
            @endcan
            @can('admin')
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Meta Management
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="{{ route('meta.make.index') }}">Make</a></li>
                        <li><a class="dropdown-item" href="{{ route('meta.factoryfit.index') }}">Factory Fit Options</a></li>
                        <li><a class="dropdown-item" href="{{ route('meta.dealerfit.index') }}">Dealer Fit Options</a></li>
                        <li><a class="dropdown-item" href="{{ route('meta.colour.index') }}">Colours</a></li>
                        <li><a class="dropdown-item" href="{{ route('meta.derivative.index') }}">Derivatives</a></li>
                        <li><a class="dropdown-item" href="{{ route('meta.engine.index') }}">Engines</a></li>
                        <li><a class="dropdown-item" href="{{ route('meta.fuel.index') }}">Fuel Types</a></li>
                        <li><a class="dropdown-item" href="{{ route('meta.transmission.index') }}">Transmissions</a></li>
                        <li><a class="dropdown-item" href="{{ route('meta.trim.index') }}">Trims</a></li>
                        <li><a class="dropdown-item" href="{{ route('meta.type.index') }}">Types</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a href="{{ route('finance.finance-type.index') }}" class="dropdown-item">Finance Type</a></li>
                        <li><a href="{{ route('finance.maintenance.index') }}" class="dropdown-item">Maintenance</a></li>
                        <li><a href="{{ route('finance.term.index') }}" class="dropdown-item">Term</a></li>
                        <li><a href="{{ route('finance.initial-payment.index') }}" class="dropdown-item">Initial Payment</a></li>
                    </ul>
                </li>
            @endcan
            @can('admin')
                <li class="nav-item @if($activePage == 'manage-updates') active @endif">
                    <a class="nav-link" href="{{ route('updates.create') }}">
                        <i class="fa-solid fa-newspaper"></i>
                        Manage Promos
                    </a>
                </li>
                <li class="nav-item @if($activePage == 'log_viewer') active @endif">
                    <a class="nav-link" href="{{ route('log-viewer.index') }}">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        View Logs
                    </a>
                </li>
            @endcan
        </ul>
    </div>
</nav>
<!-- End of Topbar -->
