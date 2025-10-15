<x-app-layout>
    @section('title')
        {{config('app.name')}} {{$question->examSubjectQuestionSection->sectionClassSubject->subject->name}} exam question view
    @endsection
    @section('breadcrumb')
       {{Breadcrumbs::render('dashboard.section.class.exam.subject.question.view',$question)}}
    @endsection
    @section('content')
    <div class="container">
        <table class="table">
        <thead>
            <tr>
                <th>Question</th>
                <th>Options</th>
                <th>Items</th>
                <th>Diagramme</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{$question->question}}</td>
                <td>
                    <table>
                        @foreach($question->options as $option)
                            <tr>
                                <td>{{$option->name}}</td>
                                <td>{{$option->value}}</td>
                                <td><a onclick="return confirm('are you sure you want to delete this Option')" href="{{route('dashboard.section.class.exam.question.delete.option',[
                                $question->examSubjectQuestionSection->sectionclassSubject->sectionClass->id, $option->id])}}">
                                <button class="btn btn-danger"><b>x</b></button></a></td>
                            </tr>
                        @endforeach
                    </table>
                    @include('section.class.exam.include.option')
                    <button data-toggle="modal" data-target="#option_{{$question->id}}" class="btn btn-secondary"><b>+</b></button>
                </td>
                <td>
                    <ol>
                    @foreach($question->questionItems as $questionItem)
                        <li>{{$questionItem->name}} <a onclick="return confirm('are you sure you want to delete this Item')" href="{{route('dashboard.section.class.exam.question.delete.item',[
                                $question->examSubjectQuestionSection->sectionclassSubject->sectionClass->id, $questionItem->id])}}">
                                <button class="btn btn-danger"><b>x</b></button></a></li> 
                    @endforeach
                    </ol>
                    <button data-toggle="modal" data-target="#item_{{$question->id}}" class="btn btn-secondary"><b>+</b></button>
                    @include('section.class.exam.include.item')
                </td>
                <td></td>
                <td>
                    
                </td>
            </tr>
        
        </tbody>
        </table>
    </div>
    @endsection    
</x-app-layout>
