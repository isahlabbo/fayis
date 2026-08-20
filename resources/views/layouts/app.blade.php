<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>FAYIS | @yield('title')</title>

  <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
  <link rel="preconnect" href="https://code.jquery.com" crossorigin>
  <link rel="dns-prefetch" href="//cdn.datatables.net">

  <!-- Stable framework assets are served locally to avoid an extra CDN round trip. -->
  <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">

  <!-- Font Awesome -->
  <link 
    rel="stylesheet" 
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
  >

  <!-- Custom Styles -->
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
  <link rel="stylesheet" href="{{ asset('css/bootstrap_modify.css') }}">
  @if(Auth::check() && Auth::user()->isSuperAdmin())
    <link rel="stylesheet" href="{{ asset('css/superadmin.css') }}?v={{ filemtime(public_path('css/superadmin.css')) }}">
  @elseif(Auth::check())
    <link rel="stylesheet" href="{{ asset('css/account-sidebar.css') }}?v={{ filemtime(public_path('css/account-sidebar.css')) }}">
  @endif
  @livewireStyles
  @yield('styles')
<style>
  
</style>
  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="{{ Auth::check() && Auth::user()->isSuperAdmin() ? 'sa-layout' : 'account-layout' }}">

  <!-- Watermark Background -->
  <div class="page-watermark"></div>

  @if(Auth::check() && Auth::user()->isSuperAdmin())
    @include('menu.superadmin-sidebar')
  @else
    @include('menu.account-sidebar')
  @endif

  <!-- Main Section -->
  <main class="{{ Auth::check() && Auth::user()->isSuperAdmin() ? 'sa-main' : 'account-main' }}">
    @include('sweetalert::alert')
    @yield('content')
  </main>

  <!-- Scripts -->
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
  <script src="{{ asset('js/bootstrap.min.js') }}"></script>
  <script src="{{ asset('js/layout.js') }}"></script>

  @livewireScripts
  @yield('scripts')

</body>
</html>
