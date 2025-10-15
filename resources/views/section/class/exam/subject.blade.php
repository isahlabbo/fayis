<x-app-layout>
    @section('title')
        {{config('app.name')}} {{$exam->sectionClass->name}} exam subject
    @endsection
    @section('breadcrumb')
       {{Breadcrumbs::render('dashboard.section.class.exam.subject',$exam)}}
    @endsection
    @section('content')
    <div class="container">
    <div class="card-header"><h4>{{$exam->sectionClass->name}} {{strtoupper($exam->academicSessionTerm->term->name)}} EXAM SUBJECTS AND QUESTIONS</h4></div>
        <table class="table">
        <thead>
            <tr>
                <th>Subject Name</th>
                <th>Question Sections</th>
                <th></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($exam->sectionClass->sectionClassSubjects as $sectionClassSubject)
            <tr>
                <td>{{$sectionClassSubject->subject->name}}</td>
                <td><a class="btn btn-primary" href="{{route('dashboard.section.class.exam.subject.question.section.index',[$exam->id,$sectionClassSubject->id])}}">
                {{count($sectionClassSubject->examSubjectQuestionSections)}}</a></td>
                <td><button data-toggle="modal" data-target="#move_{{$sectionClassSubject->id}}" class="btn btn-danger">Move all Question To</button></td>
                @include('section.class.exam.subject.question.move')
                <td>
                <td><button data-toggle="modal" data-target="#copy_{{$sectionClassSubject->id}}" class="btn btn-danger">Copy Questions From</button></td>
                @include('section.class.exam.subject.question.copy')
                <td>
                    <a href="{{route('dashboard.section.class.exam.subject.question.paper',[$exam->id, $sectionClassSubject->id])}}">
                        <button class="btn btn-secondary">Question Papers</button>
                    </a>
                    <a href="{{route('dashboard.section.class.exam.subject.question.index',[$sectionClassSubject->sectionClass->id, $sectionClassSubject->id])}}">
                        <button class="btn btn-secondary">Questions</button>
                    </a>
                    
                </td>
            </tr>
            
            @endforeach
        </tbody>
        </table>
    </div>
    @endsection    
</x-app-layout>
