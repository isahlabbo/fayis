<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>FAYIS | Welcome</title>

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

  <!-- Custom Styles -->
  <link rel="stylesheet" href="{{asset('css/style.css')}}">
  <link rel="stylesheet" href="{{asset('css/bootstrap_modify.css')}}">
</head>
<body>

  <!-- Navigation Bar -->
  <div class="page-watermark"></div>
  <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm border-bottom" style="background-color: white !important;">
    <div class="container">
      <a class="navbar-brand d-flex align-items-center" href="#home">
        <img src="{{ asset('images/welcome-logo.png') }}" alt="School Logo" height="85" width="300" class="mr-2 rounded">
      </a>

      <!-- Navbar Toggle Button -->
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
        <i class="fa fa-bars"></i>
      </button>

      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item ml-3"><a class="dropbt" href="#result"><i class="fas fa-poll-h"></i> Result</a></li>
          <li class="nav-item ml-3"><a class="dropbt" href="#about"><i class="fas fa-info-circle"></i> About Us</a></li>
          <li class="nav-item ml-3"><a class="dropbt" href="#mission-vision"><i class="fas fa-lightbulb"></i> Mission and Vision</a></li>
          <li class="nav-item ml-3"><a class="dropbt" href="#admission"><i class="fas fa-user-plus"></i> Admission</a></li>
          <li class="nav-item ml-3"><a class="dropbt" href="#academics"><i class="fas fa-book-open"></i> Academics</a></li>
          <li class="nav-item ml-3"><a class="dropbt" href="{{route('login')}}"><i class="fas fa-sign-in-alt"></i> Login</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Hero Section -->
  <section class="hero" id="home">
    <div class="hero-content">
      <h1>Welcome to Fatima Yahaya International School, Sifawa</h1>
      <p>Empowering Children for a brighter future through quality education and innovation.</p>
      <a href="#admission" class="hero-btn">Register your child Now</a>
    </div>
  </section>

  <!-- About Section -->
  <section class="about" id="about">
    <h2>About Us</h2>
    <p>Fatima Yahaya International School is dedicated to nurturing young minds and fostering academic excellence. Our mission is to provide a safe, inclusive, and stimulating environment where every student can thrive.</p>
    <div class="about-features">
      <div class="feature"><i class="fa fa-graduation-cap"></i> Qualified Teachers</div>
      <div class="feature"><i class="fa fa-globe"></i> Modern Facilities</div>
      <div class="feature"><i class="fa fa-users"></i> Community Engagement</div>
    </div>
  </section>

  <!-- Mission & Vision Section -->
  <section class="mission-vision py-5 bg-light" id="mission-vision">
    <div class="container">
      <h2 class="text-center mb-4">Our Vision & Mission</h2>
      <div class="row">
        <div class="col-md-6 mb-4">
          <div class="card shadow-sm h-100 border-0">
            <div class="card-body">
              <h4 class="card-title text-success"><i class="fa fa-eye"></i> Vision</h4>
              <p class="card-text mt-3">
                To produce visionary citizens equipped with academic excellence, strong values, ethics, and social responsibility, who will move the country toward a better future.
              </p>
            </div>
          </div>
        </div>
        <div class="col-md-6 mb-4">
          <div class="card shadow-sm h-100 border-0">
            <div class="card-body">
              <h4 class="card-title text-success"><i class="fa fa-bullseye"></i> Mission</h4>
              <p class="card-text mt-3">
                To provide a nurturing and inclusive educational environment, where all learners are inspired to achieve academic excellence, and to exhibit the attitude of responsible and committed members of the community.
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Academics Section -->
  <section class="academics" id="academics">
    <h2>Academics</h2>
    <p>We offer a broad curriculum designed to meet the needs of all students, with a focus on science, technology, arts, and humanities.</p>
    <ul class="academics-list">
      <li>Nursery</li>
      <li>Lower Basic</li>
      <li>Middle Basic</li>
      <li>Upper Basic</li>
    </ul>
  </section>

  <!-- Admission Section -->
  <section class="admission" id="admission">
    <h2>Admission</h2>
    <p>Join our vibrant learning community! Admission is open for the new academic year. Click below to start your application process.</p>
    <a href="#" class="admission-btn">Start Application</a>
  </section>

  <!-- section where parent can search the wards performance using unique code -->
  <section class="result" id="result">
    <div class="container">
      <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-6">
          <div class="card shadow-sm mb-4">
            <div class="card-body">
              <h3 class="card-title text-center text-primary">Check Your Child's Result</h3>
              <p class="text-center">Enter your child's result reference code here to view their academic performance.</p>
              <form class="result-form">
                <div class="form-group">
                  <input type="text" class="form-control" placeholder="Enter Reference Code" required>
                </div>
                <button class="btn btn-sm btn-outline-primary" type="submit">Check Result</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Contact Section -->
  <section class="contact" id="contact">
    <h2>Contact Us</h2>
    <div class="contact-info">
      <p><i class="fa fa-map-marker-alt"></i> School Address: No 2. Birnin Kebbi Road, Sifawa, Bodinga LG, Sokoto State</p>
      <p><i class="fa fa-phone"></i> Phone: +234 800 123 4567</p>
      <p><i class="fa fa-envelope"></i> Email: info@fyis.edu.ng</p>
    </div>
    <form class="contact-form">
      <input type="text" placeholder="Your Name" required>
      <input type="email" placeholder="Your Email" required>
      <textarea placeholder="Your Message" required></textarea>
      <button type="submit">Send Message</button>
    </form>
  </section>

  <!-- Footer -->
  <footer class="footer">
    <p>&copy; 2025 Hajiya Fatima Yahaya Foundation, Sifawa. All rights reserved.</p>
  </footer>

  <!-- JS Dependencies for Navbar Toggle -->
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

  <script src="{{asset('js/index.js')}}"></script>
</body>
</html>
