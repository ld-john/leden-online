<div class="nav-background-white main-sidebar">
    <ul class="navbar-nav sidebar sidebar-light accordion navbar-fixed" id="accordionSidebar">
        <!-- Sidebar - Brand -->
        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('dashboard') }}">
            <img src="{{ asset('images/leden-group-ltd.png') }}"  alt="Leden Logo"/>
        </a>
        <!-- Nav Items -->
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
        <li class="nav-item @if ($activePage == 'order-bank' || $activePage == 'deliveries' || $activePage == 'completed-orders' ) active @endif">
            <a class="nav-link" data-toggle="collapse" href="#collapseOrders" role="button" aria-expanded="false" aria-controls="collapseCSVUploads">
                <i class="fa-solid fa-chevron-down"></i>
                <span>Orders</span>
            </a>
        </li>

        <div class="collapse" id="collapseOrders">

            <li class="nav-item @if ($activePage == 'reservations') active @endif">
                <a class="nav-link" href="{{ route('reservation.index') }}">
                    <i class="fa-solid fa-bookmark"></i>
                    <span>Reservations</span>
                </a>
            </li>
            <li class="nav-item @if ($activePage == 'order-bank') active @endif">
                <a class="nav-link" href="{{ route('order_bank') }}">
                    <i class="fa-solid fa-piggy-bank"></i>
                    <span>Order Bank</span>
                </a>
            </li>
            <li class="nav-item @if ($activePage == 'deliveries') active @endif">
                <a class="nav-link" href="{{ route('manage_deliveries') }}">
                    <i class="fa-solid fa-truck"></i>
                    <span>Manage Deliveries</span>
                </a>
            </li>
            <li class="nav-item @if ($activePage == 'completed-orders') active @endif">
                <a class="nav-link" href="{{ route('completed_orders') }}">
                    <i class="fa-solid fa-clipboard-check"></i>
                    <span>Completed Orders</span>
                </a>
            </li>
        </div>
        <li class="nav-item @if ($activePage == 'pipeline' || $activePage == 'ring_fenced_stock' || $activePage == 'ford-pipeline') active @endif">
            <a class="nav-link" data-toggle="collapse" href="#collapseStock" role="button" aria-expanded="false" aria-controls="collapseCSVUploads">
                <i class="fa-solid fa-chevron-down"></i>
                <span>Stock</span>
            </a>
        </li>
        <div class="collapse" id="collapseStock">
            @can('admin')
                <li class="nav-item @if ($activePage == 'pipeline') active @endif">
                    <a class="nav-link" href="{{ route('pipeline') }}">
                        <i class="fa-solid fa-clipboard-list"></i>
                        <span>Leden Stock</span>
                    </a>
                </li>
            @endcan
            @can('broker')
                <li class="nav-item @if ($activePage == 'pipeline') active @endif">
                    <a class="nav-link" href="{{ route('pipeline') }}">
                        <i class="fa-solid fa-clipboard-list"></i>
                        <span>Leden Stock</span>
                    </a>
                </li>
            @endcan
            @can('admin')
                <li class="nav-item @if ($activePage == 'ring_fenced_stock') active @endif">
                    <a class="nav-link" href="{{ route('ring_fenced_stock') }}">
                        <i class="fa-solid fa-clipboard-list"></i>
                        <span>Ring fenced Stock</span>
                    </a>
                </li>
            @endcan
            @can('broker')
                <li class="nav-item @if ($activePage == 'ring_fenced_stock') active @endif">
                    <a class="nav-link" href="{{ route('ring_fenced_stock') }}">
                        <i class="fa-solid fa-clipboard-list"></i>
                        <span>Ring fenced Stock</span>
                    </a>
                </li>
            @endcan
            @can('admin')
                <li class="nav-item @if ($activePage == 'ford-pipeline') active @endif">
                    <a class="nav-link" href="{{ route('pipeline.ford') }}">
                        <i class="fa-solid fa-clipboard-list"></i>
                        <span>Ford Stock and Pipeline</span>
                    </a>
                </li>
            @endcan
            @can('broker')
                <li class="nav-item @if ($activePage == 'ford-pipeline') active @endif">
                    <a class="nav-link" href="{{ route('pipeline.ford') }}">
                        <i class="fa-solid fa-clipboard-list"></i>
                        <span>Ford Stock and Pipeline</span>
                    </a>
                </li>
            @endcan
        </div>
        @can('admin')
            <li class="nav-item @if ($activePage == 'csv-upload' || $activePage === 'rf-upload') active @endif">
                <a class="nav-link" data-toggle="collapse" href="#collapseCSVUploads" role="button" aria-expanded="false" aria-controls="collapseCSVUploads">
                    <i class="fa-solid fa-chevron-down"></i>
                    <span>Uploads</span>
                </a>
            </li>
            <div class="collapse" id="collapseCSVUploads">
                <li class="nav-item @if ($activePage == 'csv-upload') active @endif">
                    <a class="nav-link" href="{{ route('csv_upload') }}">
                        <i class="fa-solid fa-file-csv"></i>
                        <span>CSV Upload</span>
                    </a>
                </li>
                <li class="nav-item @if ($activePage == 'rf-upload') active @endif">
                    <a class="nav-link" href="{{ route('rf_upload') }}">
                        <i class="fa-solid fa-file-csv"></i>
                        <span>Ring Fenced Stock CSV Upload</span>
                    </a>
                </li>
                <li class="nav-item @if ($activePage == 'fit-options-upload') active @endif">
                    <a class="nav-link" href="{{ route('fit_option_upload') }}">
                        <i class="fa-solid fa-file-csv"></i>
                        <span>Fit Options CSV Upload</span>
                    </a>
                </li>
            </div>
        @endcan
        @can('admin')
            <li class="nav-item @if ($activePage == 'report-track') active @endif">
                <a class="nav-link" href="{{ route('reporting') }}">
                    <i class="fa-solid fa-chart-bar"></i>
                    <span>Report/Track Status</span>
                </a>
            </li>
        @endcan
        <li class="nav-item @if ($activePage == 'messages') active @endif">
            <a class="nav-link" href="{{ route('messages') }}">
                <i class="fa-solid fa-envelope"></i>
                <span>Messages</span>
            </a>
        </li>
        @can('admin')
            <li class="nav-item @if ($activePage == 'user-manager' || $activePage === 'company-manager' || $activePage === 'customers') active @endif">
                <a class="nav-link" data-toggle="collapse" href="#collapseListings" role="button" aria-expanded="false" aria-controls="collapseCSVUploads">
                    <i class="fa-solid fa-chevron-down"></i>
                    <span>Listings</span>
                </a>
            </li>
            <div class="collapse" id="collapseListings">
                <li class="nav-item @if ($activePage == 'user-manager') active @endif">
                    <a class="nav-link" href="{{ route('user_manager') }}">
                        <i class="fa-solid fa-users"></i>
                        <span>User Listing</span>
                    </a>
                </li>
                <li class="nav-item @if ($activePage == 'company-manager') active @endif">
                    <a class="nav-link" href="{{ route('company_manager') }}">
                        <i class="fa-solid fa-users"></i>
                        <span>Company Listings</span>
                    </a>
                </li>
                <li class="nav-item @if ($activePage === 'customers') active @endif">
                    <a class="nav-link" href="{{ route('customer.index') }}">
                        <i class="fa-solid fa-user-group"></i>
                        <span>Customers</span>
                    </a>
                </li>
            </div>
            <li class="nav-item @if($activePage=== 'vehicle-recycle-bin') active @endif">
                <a href="{{ route('vehicle.recycle_bin') }}" class="nav-link">
                    <i class="fa-solid fa-trash"></i>
                    <span>Vehicle Recycle Bin</span>
                </a>
            </li>
        @endcan
        @can('admin')
            <li class="nav-item @if ($activePage === 'dealer-fit-options' || $activePage === 'factory-fit-options' || $activePage === 'make-manager' || $activePage == 'colour-manager' || $activePage == 'derivative-manager' || $activePage == 'engine-manager' || $activePage == 'fuel-manager' || $activePage == 'transmission-manager' || $activePage == 'trim-manager' || $activePage == 'type-manager') active @endif">
                <a class="nav-link" data-toggle="collapse" href="#collapseMeta" role="button" aria-expanded="false" aria-controls="collapseCSVUploads">
                    <i class="fa-solid fa-chevron-down"></i>
                    <span>Meta Management</span>
                </a>
            </li>
            <div class="collapse" id="collapseMeta">
                <ul class="sidebar navbar-nav">
                    <li class="nav-item @if($activePage === 'make-manager') active @endif"><a class="nav-link" href="{{ route('meta.make.index') }}">Make</a></li>
                    <li class="nav-item @if ($activePage == 'factory-fit-options') active @endif"><a class="nav-link" href="{{ route('meta.factoryfit.index') }}">Factory Fit Options</a></li>
                    <li class="nav-item @if ($activePage == 'dealer-fit-options') active @endif"><a class="nav-link" href="{{ route('meta.dealerfit.index') }}">Dealer Fit Options</a></li>
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
        @can('admin')
            <li class="nav-item @if($activePage == 'manage-updates') active @endif"><a class="nav-link" href="{{ route('updates.create') }}"><i class="fa-solid fa-newspaper"></i> Manage News and Promos</a></li>
        @endcan
    </ul>

</div>


<!-- End of Sidebar -->
