<x-app-layout>
    @section('title')
        {{config('app.name')}} {{$sectionClassStudent->student->name}} accessment
    @endsection
    @section('breadcrumb')
       {{Breadcrumbs::render('dashboard')}}
    @endsection
    @php
       $comments = App\Models\TeacherComment::all();
    @endphp
    @section('content')
    <div class="card shadow">
    <div class="card-body ">
    <div class="card-header shadow h5 text-center"><b>{{$sectionClassStudent->student->name}} Accessment</b></div><br>
        <form action="{{route('dashboard.student.accessment.register',[$sectionClassStudent->currentStudentTerm()->id])}}" method="post">
            @csrf
            <div class="row">
            <input type="hidden" name="sectionClassStudentTermId" value="{{$sectionClassStudent->currentStudentTerm()->id}}">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-header shadow text-center">EFFECTIVE TRAIT</div><br>
                            @include('school.student.accessment.forms.effectiveTrait')
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-header shadow text-center">PSYCHOMOTOR</div><br>
                            @include('school.student.accessment.forms.psychomotor')
                        </div>
                    </div><br>
                    <div class="card">
                        <div class="card-body">
                            <div class="card-header shadow text-center">ATTENDANCE</div><br>
                            @include('school.student.accessment.forms.attendance')
                        </div>
                    </div>
                </div>
                
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-header shadow text-center">FORM TEACHER REMARK</div><br>
                            @include('school.student.accessment.forms.comment')
                            <button class="btn btn-secondary">Submit</button>
                        </div>
                    </div>
                </div>
            </div>   
        </form>
      </div>
      </div>
    @endsection
    
</x-app-layout>
