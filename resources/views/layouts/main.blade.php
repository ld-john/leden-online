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

  <!-- Styles -->
  <link href="{{ asset('css/app.css') }}" rel="stylesheet">
  <link href="{{ asset('css/bootstrap-select.css') }}" rel="stylesheet">
  <link href="{{ asset('css/bootstrap-datepicker.css') }}" rel="stylesheet">
  <link href="{{ asset('css/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
  <livewire:styles />

  <!-- Scripts -->
  <script src="{{ asset('js/custom.js') }}" defer></script>

</head>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    @include('partials.sidebar')
    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">
      <!-- Main Content -->
      <div id="content">
        @include('partials.top_bar')
        @yield('content')
      </div>
    </div>
    <!-- End of Content Wrapper -->

  </div>
  <!-- End of Page Wrapper -->

  @include('partials.footer')

  <!-- Bootstrap core JavaScript-->
  <script src="{{ asset('js/jquery/jquery.min.js') }}"></script>
  <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('js/bootstrap-select.js') }}"></script>
  <script src="{{ asset('js/bootstrap-datepicker.js') }}"></script>
  <script src="{{ asset('js/jquery-easing/jquery.easing.min.js') }}"></script>
  <script src="{{ asset('js/Chart.min.js') }}"></script>
  <livewire:scripts />

  <!-- Custom scripts for all pages-->
  <script src="{{ asset('js/dashboard.js') }}"></script>
  @stack('custom-scripts')

</body>

</html>
