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


<!-- Content Wrapper -->
<div id="content-wrapper">
  <!-- Main Content -->
  <div id="content">
    @include('partials.top_bar')
    <x-notify::notify />
    @yield('content')
  </div>
</div>
<!-- End of Content Wrapper -->
<!-- End of Page Wrapper -->

@include('partials.footer')

<!-- Bootstrap core JavaScript-->
<script src="{{ asset('js/jquery/jquery.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
<script src="{{ asset('js/bootstrap-select.js') }}"></script>
<script src="{{ asset('js/bootstrap-datepicker.js') }}"></script>
<script src="{{ asset('js/jquery-easing/jquery.easing.min.js') }}"></script>
<script src="{{ asset('js/Chart.min.js') }}"></script>
@vite(['resources/sass/app.scss', 'resources/js/app.js'])
@livewireScriptConfig
@notifyJs

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
