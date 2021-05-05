<ul class="navbar-nav nav-background-white sidebar sidebar-light accordion navbar-fixed" id="accordionSidebar">
    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('dashboard') }}">
        <img src="{{ asset('images/leden-group-ltd.png') }}" />
    </a>
    <!-- Nav Items -->
    <li class="nav-item @if ($activePage == 'dashboard') active @endif">
        <a class="nav-link" href="{{ route('dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>
    @if (Helper::roleCheck(Auth::user()->id)->role == 'admin')
        <li class="nav-item @if ($activePage == 'create-order') active @endif">
            <a class="nav-link" href="{{ route('create_order') }}">
                <i class="fas fa-fw fa-plus"></i>
                <span>Create Order</span>
            </a>
        </li>
    @endif
    <li class="nav-item @if ($activePage == 'order-bank') active @endif">
        <a class="nav-link" href="{{ route('order_bank') }}">
            <i class="fas fa-fw fa-piggy-bank"></i>
            <span>Order Bank</span>
        </a>
    </li>
    <li class="nav-item @if ($activePage == 'deliveries') active @endif">
        <a class="nav-link" href="{{ route('manage_deliveries') }}">
            <i class="fas fa-fw fa-truck"></i>
            <span>Manage Deliveries</span>
        </a>
    </li>
    <li class="nav-item @if ($activePage == 'completed-orders') active @endif">
        <a class="nav-link" href="{{ route('completed_orders') }}">
            <i class="fas fa-fw fa-clipboard-check"></i>
            <span>Completed Orders</span>
        </a>
    </li>
    <li class="nav-item @if ($activePage == 'pipeline') active @endif">
        <a class="nav-link" href="{{ route('pipeline') }}">
            <i class="fas fa-fw fa-clipboard-list"></i>
            <span>Leden Stock</span>
        </a>
    </li>
    @if (in_array(Helper::roleCheck(Auth::user()->id)->role, ['admin', 'broker']))
        <li class="nav-item @if ($activePage == 'ford-pipeline') active @endif">
            <a class="nav-link" href="{{ route('pipeline.ford') }}">
                <i class="fas fa-fw fa-clipboard-list"></i>
                <span>Ford Stock and Pipeline</span>
            </a>
        </li>
    @endif
    @if (Helper::roleCheck(Auth::user()->id)->role == 'admin')
        <li class="nav-item @if ($activePage == 'csv-upload') active @endif">
            <a class="nav-link" href="{{ route('csv_upload') }}">
                <i class="fas fa-fw fa-file-csv"></i>
                <span>CSV Upload</span>
            </a>
        </li>
    @endif
    @if (Helper::roleCheck(Auth::user()->id)->role == 'admin')
        <li class="nav-item @if ($activePage == 'report-track') active @endif">
            <a class="nav-link" href="{{ route('reporting') }}">
                <i class="fas fa-fw fa-chart-bar"></i>
                <span>Report/Track Status</span>
            </a>
        </li>
    @endif
    <li class="nav-item @if ($activePage == 'messages') active @endif">
        <a class="nav-link" href="{{ route('messages') }}">
            <i class="fas fa-fw fa-envelope"></i>
            <span>Messages</span>
        </a>
    </li>
    @if (Helper::roleCheck(Auth::user()->id)->role == 'admin')
        <li class="nav-item @if ($activePage == 'user-manager') active @endif">
            <a class="nav-link" href="{{ route('user_manager') }}">
                <i class="fas fa-fw fa-users"></i>
                <span>User Listing</span>
            </a>
        </li>
    @endif
</ul>
<!-- End of Sidebar -->
