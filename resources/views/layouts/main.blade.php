<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <link rel="shortcut icon" href="{{ asset('images/favicon.png') }}">

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>Leden | {{ $title }}</title>

  <link href="{{ asset('css/font-awesome/all.min.css') }}" rel="stylesheet" type="text/css">

  <!-- Styles -->
  <link href="{{ asset('css/app.css') }}" rel="stylesheet">
  <link href="{{ asset('css/bootstrap-select.css') }}" rel="stylesheet">
  <link href="{{ asset('css/bootstrap-datepicker.css') }}" rel="stylesheet">
  <link href="{{ asset('css/dataTables.bootstrap4.min.css') }}" rel="stylesheet">

  <!-- Scripts -->
  <script src="{{ asset('js/custom.js') }}" defer></script>

</head>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <ul class="navbar-nav nav-background-white sidebar sidebar-light accordion navbar-fixed" id="accordionSidebar">

      <!-- Sidebar - Brand -->
      <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('dashboard') }}">
        <img src="{{ asset('images/leden-group-ltd.png') }}" />
      </a>

      <!-- Nav Items -->
      <li class="nav-item @if ($activePage == 'dashboard') active @endif">
        <a class="nav-link" href="{{ route('dashboard') }}">
          <i class="fas fa-fw fa-tachometer-alt"></i>
          <span>Dashboard</span></a>
      </li>

      @if (Helper::roleCheck(Auth::user()->id)->role == 'admin')
      <li class="nav-item @if ($activePage == 'create-order') active @endif">
        <a class="nav-link" href="{{ route('create_order') }}">
          <i class="fas fa-fw fa-plus"></i>
          <span>Create Order</span></a>
      </li>
      @endif

        <li class="nav-item @if ($activePage == 'order-bank') active @endif">
            <a class="nav-link" href="{{ route('order_bank') }}">
                <i class="fas fa-fw fa-piggy-bank"></i>
                <span>Order Bank</span></a>
        </li>

        <li class="nav-item @if ($activePage == 'deliveries') active @endif">
            <a class="nav-link" href="{{ route('manage_deliveries') }}">
                <i class="fas fa-fw fa-truck"></i>
                <span>Manage Deliveries</span></a>
        </li>


        <li class="nav-item @if ($activePage == 'completed-orders') active @endif">
        <a class="nav-link" href="{{ route('completed_orders') }}">
          <i class="fas fa-fw fa-clipboard-check"></i>
          <span>Completed Orders</span></a>
      </li>

        <li class="nav-item @if ($activePage == 'pipeline') active @endif">
            <a class="nav-link" href="{{ route('pipeline') }}">
                <i class="fas fa-fw fa-clipboard-list"></i>
                <span>Leden Stock</span></a>
        </li>
        @if (in_array(Helper::roleCheck(Auth::user()->id)->role, ['admin', 'broker']))
        <li class="nav-item @if ($activePage == 'ford-pipeline') active @endif">
            <a class="nav-link" href="{{ route('pipeline.ford') }}">
                <i class="fas fa-fw fa-clipboard-list"></i>
                <span>Ford Stock and Pipeline</span></a>
        </li>
        @endif
        @if (Helper::roleCheck(Auth::user()->id)->role == 'admin')
            <li class="nav-item @if ($activePage == 'csv-upload') active @endif">
                <a class="nav-link" href="{{ route('csv_upload') }}">
                    <i class="fas fa-fw fa-file-csv"></i>
                    <span>CSV Upload</span></a>
            </li>
        @endif


      @if (Helper::roleCheck(Auth::user()->id)->role == 'admin')
      <li class="nav-item @if ($activePage == 'report-track') active @endif">
        <a class="nav-link" href="{{ route('reporting') }}">
          <i class="fas fa-fw fa-chart-bar"></i>
          <span>Report/Track Status</span></a>
      </li>
      @endif




      <li class="nav-item @if ($activePage == 'messages') active @endif">
        <a class="nav-link" href="{{ route('messages') }}">
          <i class="fas fa-fw fa-envelope"></i>
          <span>Messages</span></a>
      </li>

      @if (Helper::roleCheck(Auth::user()->id)->role == 'admin')
      <li class="nav-item @if ($activePage == 'user-manager') active @endif">
        <a class="nav-link" href="{{ route('user_manager') }}">
          <i class="fas fa-fw fa-users"></i>
          <span>User Listing</span></a>
      </li>
      @endif
        @if (Helper::roleCheck(Auth::user()->id)->role == 'admin')
        <!--<li class="nav-item @if ($activePage == 'custom-reports') active @endif">
        <a class="nav-link" href="{{ route('custom_reports') }}">
          <i class="fas fa-fw fa-chart-line"></i>
          <span>Custom Reports</span></a>
      </li>-->
        @endif
    </ul>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <!-- Topbar -->
        <nav class="navbar navbar-expand navbar-light blue-background topbar mb-4 fixed-top shadow">

          <!-- Sidebar Toggle (Topbar) -->
          <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
            <i class="fa fa-bars"></i>
          </button>

          <!-- Topbar Navbar -->
          <ul class="navbar-nav ml-auto">

            <!-- Nav Item - Alerts -->
            <li class="nav-item dropdown no-arrow mx-1">
              <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
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
                @foreach (Auth::user()->unreadNotifications->take(4) as $notification)
                <a class="dropdown-item d-flex align-items-center" href="{{ route('order.show', $notification->data['order_id']) }}">
                  <div class="mr-3">
                    <div class="icon-circle blue-background">
                      @if ($notification->data['type'] == 'vehicle')
                      <i class="fas fa-car text-white"></i>
                      @else
                      <i class="fas fa-flag text-white"></i>
                      @endif
                    </div>
                  </div>
                  <div>
                    <div class="small text-gray-500">{{ date('l jS F Y \a\t g:ia', strtotime($notification->created_at)) }}</div>
                    <span class="@if ($notification->read_at == null) font-weight-bold @endif ">{{ $notification->data['message'] }}</span>
                  </div>
                </a>
                @endforeach
                <a class="dropdown-item text-center small text-gray-500" href="{{ route('notifications') }}">View All Notifications</a>
              </div>
            </li>

            <!-- Nav Item - Messages -->
            <li class="nav-item dropdown no-arrow mx-1">
              <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
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
              <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
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
        @yield('content')

        <!-- Footer -->
      <footer class="sticky-footer blue-background">
        <div class="container my-auto">
          <div class="copyright text-center my-auto">
            <span style="color:#fff;">Copyright &copy; <?php echo date('Y'); ?> The Leden Group Limited, All rights reserved.</span>
          </div>
        </div>
      </footer>
      <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

  </div>
  <!-- End of Page Wrapper -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <!-- Bootstrap core JavaScript-->
  <script src="{{ asset('js/jquery/jquery.min.js') }}"></script>
  <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('js/bootstrap-select.js') }}"></script>
  <script src="{{ asset('js/bootstrap-datepicker.js') }}"></script>
  <script src="{{ asset('js/jquery-easing/jquery.easing.min.js') }}"></script>
  <script src="{{ asset('js/Chart.min.js') }}"></script>

  <!-- Custom scripts for all pages-->
  <script src="{{ asset('js/dashboard.js') }}"></script>
  @stack('custom-scripts')

</body>

</html>
