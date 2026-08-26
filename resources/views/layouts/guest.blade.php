<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>FAYIS @yield('title')</title>
  <link rel="stylesheet" href="{{asset('css/style.css')}}">
  
  <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
  <link rel="preconnect" href="https://code.jquery.com" crossorigin>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{asset('css/bootstrap_modify.css')}}?v={{ filemtime(public_path('css/bootstrap_modify.css')) }}">
  @yield('styles')
</head>
<body>
  <!-- Navigation Bar -->
  

  <!-- Hero Section -->
  <section class="container pb-4">
    <br>
    <br>
    @include('sweetalert::alert')
    @yield('content')
  </section>

  

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<script src="{{asset('js/index.js')}}"></script>
<script>
    function printContent(el) {
      var restorepage = document.body.innerHTML;
      var source = document.getElementById(el);
      if (!source) return;
      document.body.innerHTML = source.innerHTML;
      window.print();
      document.body.innerHTML = restorepage;
      window.location.reload();
    }
  </script>
</body>
</html>
