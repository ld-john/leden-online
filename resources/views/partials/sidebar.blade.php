<div class="nav-background-white">
<ul class="navbar-nav sidebar sidebar-light accordion navbar-fixed" id="accordionSidebar">
    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('dashboard') }}">
        <img src="{{ asset('images/leden-group-ltd.png') }}"  alt="Leden Logo"/>
    </a>
    <!-- Nav Items -->
    <li class="nav-item @if ($activePage == 'dashboard') active @endif">
        <a class="nav-link" href="{{ route('dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>
    @can('admin')
        <li class="nav-item @if ($activePage == 'create-order') active @endif">
            <a class="nav-link" href="{{ route('create_order') }}">
                <i class="fas fa-fw fa-plus"></i>
                <span>Create Order</span>
            </a>
        </li>
    @endcan
    @can('admin')
        <li class="nav-item @if ($activePage == 'create-vehicle') active @endif">
            <a class="nav-link" href="{{ route('create_vehicle') }}">
                <i class="fas fa-fw fa-plus"></i>
                <span>Create Vehicle</span>
            </a>
        </li>
    @endcan
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
    @can('admin')
        <li class="nav-item @if ($activePage == 'ford-pipeline') active @endif">
            <a class="nav-link" href="{{ route('pipeline.ford') }}">
                <i class="fas fa-fw fa-clipboard-list"></i>
                <span>Ford Stock and Pipeline</span>
            </a>
        </li>
    @endcan
    @can('broker')
        <li class="nav-item @if ($activePage == 'ford-pipeline') active @endif">
            <a class="nav-link" href="{{ route('pipeline.ford') }}">
                <i class="fas fa-fw fa-clipboard-list"></i>
                <span>Ford Stock and Pipeline</span>
            </a>
        </li>
    @endcan
    @can('admin')
        <li class="nav-item @if ($activePage == 'csv-upload') active @endif">
            <a class="nav-link" href="{{ route('csv_upload') }}">
                <i class="fas fa-fw fa-file-csv"></i>
                <span>CSV Upload</span>
            </a>
        </li>
    @endcan
    @can('admin')
        <li class="nav-item @if ($activePage == 'report-track') active @endif">
            <a class="nav-link" href="{{ route('reporting') }}">
                <i class="fas fa-fw fa-chart-bar"></i>
                <span>Report/Track Status</span>
            </a>
        </li>
    @endcan
    <li class="nav-item @if ($activePage == 'messages') active @endif">
        <a class="nav-link" href="{{ route('messages') }}">
            <i class="fas fa-fw fa-envelope"></i>
            <span>Messages</span>
        </a>
    </li>
    @can('admin')
        <li class="nav-item @if ($activePage == 'user-manager') active @endif">
            <a class="nav-link" href="{{ route('user_manager') }}">
                <i class="fas fa-fw fa-users"></i>
                <span>User Listing</span>
            </a>
        </li>
    @endcan
</ul>

@can('admin')
    <div class="meta-management">

        <h4>Meta Management</h4>

        <ul class="sidebar navbar-nav">
            <li class="nav-item @if ($activePage == 'colour-manager') active @endif"><a class="nav-link" href="{{ route('meta.colour.index') }}">Colours </a></li>
            <li class="nav-item @if ($activePage == 'derivative-manager') active @endif"><a class="nav-link" href="{{ route('meta.derivative.index') }}">Derivatives </a></li>
            <li class="nav-item @if ($activePage == 'engine-manager') active @endif"><a class="nav-link" href="{{ route('meta.engine.index') }}">Engines</a></li>
            <li class="nav-item @if ($activePage == 'fuel-manager') active @endif"><a class="nav-link" href="{{ route('meta.fuel.index') }}">Fuel Types</a></li>
            <li class="nav-item @if ($activePage == 'transmission-manager') active @endif"><a class="nav-link" href="{{ route('meta.transmission.index') }}">Transmissions</a></li>
            <li class="nav-item @if ($activePage == 'trim-manager') active @endif"><a class="nav-link" href="{{ route('meta.trim.index') }}">Trims</a></li>
            <li class="nav-item @if ($activePage == 'type-manager') active @endif"><a class="nav-link" href="{{ route('meta.type.index') }}">Types</a></li>
        </ul>

    </div>

@endcan
<!-- End of Sidebar -->

</div>
