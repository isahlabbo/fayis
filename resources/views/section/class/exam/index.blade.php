<x-app-layout>
    @section('title')
        {{config('app.name')}} {{$sectionClass->name}} exam setting
    @endsection
    @section('breadcrumb')
       {{Breadcrumbs::render('dashboard.section.class.exam',$sectionClass)}}
    @endsection
    @section('content')
    <div class="container">
        <table class="table">
        <thead>
            <tr>
                <th>Session</th>
                <th>Term</th>
                <th>Subjects</th>
                <th>Status</th>
                <th><button class="btn btn-secondary" data-toggle="modal" data-target="#newExam">Add Exam</button></th>
                @include('section.class.exam.create')
            </tr>
        </thead>
        <tbody>
            @foreach($sectionClass->sectionClassTermlyExams as $sectionClassTermlyExam)
            <tr>
                <td>{{$sectionClassTermlyExam->academicSessionTerm->academicSession->name}}</td>
                <td>{{$sectionClassTermlyExam->academicSessionTerm->term->name}}</td>
                <td>
                <a href="{{route('dashboard.section.class.exam.subject',[$sectionClass->id, $sectionClassTermlyExam->id])}}">
                {{count($sectionClass->sectionClassSubjects)}}</a>
                </td>
                <td>{{$sectionClassTermlyExam->status}}</td>
                <td>
                    <button class="btn btn-primary">Edit</button>
                    <button class="btn btn-danger">Delete</button>
                </td>
            </tr>
            @endforeach
        </tbody>
        </table>
    </div>
    @endsection    
</x-app-layout>
