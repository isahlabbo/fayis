<x-app-layout>
    @section('title')
        {{config('app.name')}} {{$sectionClassSubject->subject->name}} exam questions
    @endsection
    @section('breadcrumb')
       {{Breadcrumbs::render('dashboard.section.class.exam.subject.question.section',$sectionClassSubject->currentExam(),$sectionClassSubject)}}
    @endsection
    @section('content')
    
    <div class="container">
        <table class="table">
        <thead>
            <tr>
                <th>S/N</th>
                <th>Section Name</th>
                <th>Section Instruction</th>
                <th><button data-toggle="modal" data-target="#addSection" class="btn btn-primary">Add Question Section</button></th>
            </tr>
            @include('section.class.exam.subject.question.section.create')
        </thead>
        <tbody>
            @foreach($sectionClassSubject->examSubjectQuestionSections as $section)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>{{$section->name}}</td>
                        <td>{{$section->instruction}}</td>
                        <td>
                            <button data-toggle="modal" data-target="#edit_{{$section->id}}" class="btn btn-primary">Edit</button>
                            <button class="btn btn-danger">Delete</button>
                        </td>
                    </tr>
            @endforeach
        </tbody>
        </table>
    </div>
    @endsection    
</x-app-layout>
