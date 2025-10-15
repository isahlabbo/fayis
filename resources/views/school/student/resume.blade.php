<x-app-layout>
    @section('title')
        {{config('app.name')}} students
    @endsection
    @section('breadcrumb')
       {{Breadcrumbs::render('dashboard.student')}}
    @endsection
    
    @section('content')
        <div class="card shadow">
            <div class="card-body">
                <p class="h4 text text-success">Congratulation!</p>
                <p class="h5 text text-info">According to the academic session configuration we are 
                    expecting that all the academic activities for the
                     {{$academicSessionTerm->term->name}} of {{$academicSessionTerm->academicSession->name}} 
                     Academic Session has finished and you are ready to start the {{$academicSessionTerm->academicSession->nextAcademicSessionTerm($academicSessionTerm->term)->term->name}}</p><br>
                <p class="h5 text text-warning">Note: if the statement above in not correct or you are not ready for the  {{$academicSessionTerm->academicSession->nextAcademicSessionTerm($academicSessionTerm->term)->term->name}} click 
                    <a href="{{route('dashboard.session.configure',[$academicSessionTerm->academicSession->id])}}">Session Configuration</a> to adjust your academic session configuration</p><br>
                <p>
                <p>Prove: the {{$academicSessionTerm->term->name}} ends at {{date('M-d-Y',strtotime($academicSessionTerm->end_at))}} and we are now at {{date('M-d-Y')}}</p>    
                    <a href="{{route('dashboard.student.resume.confirm',[$academicSessionTerm->id])}}">
                        <button class="btn btn-block btn-danger">Confirm Resumption For The {{$academicSessionTerm->academicSession->nextAcademicSessionTerm($academicSessionTerm->term)->term->name}} of {{$academicSessionTerm->academicSession->name}}</button>
                    </a>
                </p>
            </div>
        </div>
    @endsection
    
</x-app-layout>
