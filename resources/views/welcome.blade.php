<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>FAYIS | welcome</title>
  <link 
    rel="stylesheet" 
    href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
  >
  <link rel="stylesheet" href="{{asset('css/style.css')}}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <link rel="stylesheet" href="{{asset('css/bootstrap_modify.css')}}">
  
</head>
<body>
  <!-- Navigation Bar -->
  <!-- Watermark Background -->
  <div class="page-watermark"></div>

  <!-- Navigation Bar -->
  <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm border-bottom" style="background-color: white !important;">
    <div class="container">
      <a class="navbar-brand d-flex align-items-center" href="{{route('dashboard')}}">
        <img src="{{ asset('images/logo.jpg') }}" alt="School Logo" width="70" class="mr-2 rounded">
        <span class="fw-bold text-success">Fatima Yahaya International School<br>
        <small style="color: #ff6600;"><i>No 2. Birnin Kebbi Road, Sifawa, Bodinga LG, Sokoto</i></small></span>
      </a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
        <i class="fa fa-bars"></i>
      </button>

      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item ml-3">
            <a class="dropbt" href="{{route('login')}}">
              <i class="fas fa-clipboard-check"></i> Result
            </a>
          </li>
          <li class="nav-item ml-3">
            <a class="dropbt" href="{{route('login')}}">
              <i class="fas fa-calendar"></i> Calendar
            </a>
          </li>
          <li class="nav-item ml-3">
            <a class="dropbt" href="{{route('login')}}">
              <i class="fas fa-file-signature"></i> Application
            </a>
          </li>
          <li class="nav-item ml-3">
            <a class="dropbt" href="{{route('login')}}">
              <i class="fas fa-user-graduate"></i> Admission
            </a>
          </li>
          <li class="nav-item ml-3">
            <a class="dropbt" href="{{route('login')}}">
              <i class="fas fa-file-alt"></i> Academics
            </a>
          </li>
          <li class="nav-item ml-3">
            <a class="dropbt" href="{{route('login')}}">
              <i class="fas fa-sign-in-alt"></i> Login
            </a>
          </li>
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

  <!-- Academics Section -->
  <section class="academics" id="academics">
    <h2>Academics</h2>
    <p>We offer a broad curriculum designed to meet the needs of all students, with a focus on science, technology, arts, and humanities.</p>
    <ul class="academics-list">
      <li>Islamiyya</li>
      <li>Nursery</li>
      <li>Lower Basic</li>
      <li>Uper Basic</li>
      <li>Junior Secondary</li>
    </ul>
  </section>

  <!-- Admission Section -->
  <section class="admission" id="admission">
    <h2>Admission</h2>
    <p>Join our vibrant learning community! Admission is open for the new academic year. Click below to start your application process.</p>
    <a href="#" class="admission-btn">Start Application</a>
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

  <script src="{{asset('js/index.js')}}"></script>
</body>
</html>
