<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>FAYIS @yield('title')</title>
  <link rel="stylesheet" href="{{asset('css/style.css')}}">
  
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="{{asset('css/bootstrap_modify.css')}}">
</head>
<body>
  <!-- Navigation Bar -->
  

  <!-- Hero Section -->
  <section class="container pb-4">
    <br>
    <br>
    @yield('content')
  </section>

  

<script src="{{asset('js/index.js')}}"></script>
</body>
</html>
