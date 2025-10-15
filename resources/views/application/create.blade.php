@extends('layouts.app')

@section('title')
    {{$token->pin}} application
@endsection

@section('content')



<form action="{{route('dashboard.application.register',[$token->id])}}" enctype="multipart/form-data" method="post" id="multiLevelForm">
    @csrf
    <div class="row">
        <div class="col-md-2">
            <img  src="{{asset('assets/images/logo-main.png')}}" alt="" >
        </div>
        <div class="col-md-7 center">
            <h4 class="text-primary">ACADEMIC AFFAIRS DEVISION</h2>
            <h3>APPLICATION FORM FOR ADMISSION</h1>
            <h5 class="text-secondary">ACADEMIC SESSION</h3>
        </div>
        <div class="col-md-3 center">
            <div class="token mb-2">Token: {{$token->pin}}</div>
            <img id="picture_preview_container" src="{{asset('assets/images/user.jpg')}}" alt="" width="150" height="150">
        </div>
    </div>
    <div class="progress mb-3">
        <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
    </div>
    <div class="form-level" data-level="1">
        <h2 class="text-primary">PERSONAL RECORDS</h2>
        <!-- personal record start -->
        @include('application.form.personal')
        <!-- personal record end -->
      <button type="button" class="btn btn-primary next-level">Next</button>
    </div>

    <!-- Level 2 -->
    <div class="form-level d-none" data-level="2">
        <h2 class="text-primary">EDUCATIONAL RECORDS</h2>
        <!-- personal record start -->
        @include('application.form.educational')
        <!-- personal record end -->
      <button type="button" class="btn btn-secondary prev-level">Previous</button>
      <button type="button" class="btn btn-primary next-level">Next</button>
    </div>

    <!-- Level 3 -->
    <div class="form-level d-none" data-level="3">
      <h2 class="text-primary">KNOWLEDGE OF THE GLOROUS QUR'AN</h2>
        <!-- personal record start -->
        @include('application.form.quran_knowledge')
        <!-- personal record end -->
      <button type="button" class="btn btn-secondary prev-level">Previous</button>
      <button type="button" class="btn btn-primary next-level">Next</button>
    </div>

    <!-- Level 4 -->
    <div class="form-level d-none" data-level="4">
      <h2 class="text-primary">PROFICIENCY IN LANGUAGES</h2>
        <!-- personal record start -->
        @include('application.form.language_proficiency')
        <!-- personal record end -->
      <button type="button" class="btn btn-secondary prev-level">Previous</button>
      <button type="button" class="btn btn-primary next-level">Next</button>
    </div>

    <!-- Level 5 -->
    <div class="form-level d-none" data-level="5">
        <h2 class="text-primary">OTHER MEDICAL RECORDS</h2>
        <!-- personal record start -->
        @include('application.form.medical_record')
        <!-- personal record end -->
      <button type="button" class="btn btn-secondary prev-level">Previous</button>
      <button type="button" class="btn btn-primary next-level">Next</button>
    </div>

    <!-- Level 6 -->
    <div class="form-level d-none" data-level="6">
        <h2 class="text-primary">DECLARATION</h2>
        <!-- personal record start -->
        @include('application.form.declaration')
        <!-- personal record end -->
      <button type="button" class="btn btn-secondary prev-level">Previous</button>
      <button type="submit" class="btn btn-success">Submit</button>
    </div>
  </form>
    
@endsection

@section('scripts')
<script>
  $(document).ready(function () {
    let currentLevel = 1;
    const totalLevels = 6;

    $(".next-level").click(function () {
      if (validateInputs()) {
        if (currentLevel < totalLevels) {
          $(`.form-level[data-level="${currentLevel}"]`).addClass("d-none");
          currentLevel++;
          updateProgressBar();
          $(`.form-level[data-level="${currentLevel}"]`).removeClass("d-none");
        }
      }
    });

    $(".prev-level").click(function () {
      if (currentLevel > 1) {
        $(`.form-level[data-level="${currentLevel}"]`).addClass("d-none");
        currentLevel--;
        updateProgressBar();
        $(`.form-level[data-level="${currentLevel}"]`).removeClass("d-none");
      }
    });

    function updateProgressBar() {
      const progress = ((currentLevel - 1) / (totalLevels - 1)) * 100;
      $(".progress-bar").css("width", `${progress}%`).attr("aria-valuenow", progress);
    }

    function validateInputs() {
      const requiredInputs = $(`.form-level[data-level="${currentLevel}"] input.required`);
      let isValid = true;

      requiredInputs.each(function () {
        if (!$(this).val()) {
          isValid = false;
          // You may customize error handling, e.g., displaying a message
          alert("Please fill in all required fields before proceeding.");
          return false; // Stop the loop early
        }
      });

      return isValid;
    }

    // Initial progress bar setup
    updateProgressBar();
  });
</script>
@endsection