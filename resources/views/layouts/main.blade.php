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
  @notifyCss
  <link href="{{ asset('css/app.css') }}" rel="stylesheet">
  <link href="{{ asset('css/bootstrap-select.css') }}" rel="stylesheet">
  <link href="{{ asset('css/bootstrap-datepicker.css') }}" rel="stylesheet">
  <link href="{{ asset('css/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <livewire:styles />
  @stack('custom-styles')

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

        <div class="content-container">
          <x:notify-messages />
          @yield('content')
        </div>
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
  @notifyJs
  <livewire:scripts />

  <!-- Custom scripts for all pages-->
  <script src="{{ asset('js/dashboard.js') }}"></script>
  @stack('custom-scripts')
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <script>
    // In your Javascript (external .js resource or <script> tag)
    $(document).ready(function() {
      $('.customer_select').select2({
        placeholder: "Select existing Customer",
        allowClear: true
      });
      $('.select2').select2({});
    });
  </script>

</body>

</html>
