<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>FAYIS | @yield('title')</title>

  <!-- Bootstrap Core CSS -->
  <link 
    rel="stylesheet" 
    href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
  >

  <!-- Font Awesome -->
  <link 
    rel="stylesheet" 
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
  >

  <!-- Custom Styles -->
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
  <link rel="stylesheet" href="{{ asset('css/bootstrap_modify.css') }}">
<style>
  
</style>
  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>

  <!-- Watermark Background -->
  <div class="page-watermark"></div>

  <!-- Navigation Bar -->
  <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm border-bottom" style="background-color: white !important;">
    <div class="container">
      <a class="navbar-brand d-flex align-items-center" href="{{route('dashboard')}}">
        <img src="{{ asset('images/logo.jpg') }}" alt="School Logo" width="70" class="mr-2 rounded">
        <span class="fw-bold text-success">FAYIS</span>
      </a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
        <i class="fa fa-bars"></i>
      </button>

      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">

          @if(Auth::user()->role == 'admin')
              @include('menu.admin')
          @elseif(Auth::user()->role == 'head')
              @include('menu.head')
          @elseif(Auth::user()->role == 'admission_officer')
              @include('menu.admission_officer')
          @elseif(Auth::user()->role == 'exam_officer')
              @include('menu.exam_officer')
          @elseif(Auth::user()->role == 'finance_officer')
              @include('menu.finance_officer')
          @else
              @include('menu.teacher')
          @endif

          <li class="nav-item ml-3">
            <a class="dropbt" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
              <i class="fas fa-sign-out-alt"></i> Logout
            </a>
          </li>
          <form id="logout-form" action="{{route('logout')}}" method="POST">@csrf</form>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Main Section -->
  <main class="container py-4">
    @include('sweetalert::alert')
    @yield('content')
  </main>

  <!-- Scripts -->
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js"></script>

  <script src="{{ asset('js/Ajax/address.js') }}"></script>
  <script src="{{ asset('js/Ajax/sectionClasses.js') }}"></script>
  <script src="{{ asset('assets/js/spinner.js') }}"></script>

  <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
  <script>
    $(document).ready(function(){
      $('#myTable').DataTable();
    });
  </script>

  <script>
    $(document).ready(function () {
      $.ajaxSetup({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
      });
      $('#picture').change(function(){
        let reader = new FileReader();
        reader.onload = (e) => $('#picture_preview_container').attr('src', e.target.result);
        reader.readAsDataURL(this.files[0]);
      });
    });
  </script>

  <script>
    function printContent(el) {
      var restorepage = $('body').html();
      var printcontent = $('#' + el).clone();
      $('body').empty().html(printcontent);
      window.print();
      $('body').html(restorepage);
    }
  </script>

  @yield('scripts')

</body>
</html>
