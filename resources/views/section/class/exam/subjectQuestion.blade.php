<x-app-layout>
    @section('title')
        {{config('app.name')}} {{$exam->sectionClass->name}} exam question papers
    @endsection
    @section('breadcrumb')
       {{Breadcrumbs::render('dashboard')}}
    @endsection
    @section('content')
    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-1"><button class="btn btn-secondary btn-block" id="print" onclick="printContent('report');" >Print</button></div>
    </div><br>
    <div id="report">
    @foreach($exam->sectionClass->sectionClassStudents->where('status','Active') as $sectionClassStudent)
    <div class="card" style="page-break-inside: avoid; color:black;">
        <div class="card-body">
            <div class="row">
                @include('section.class.student.result.reportcard.component.schoolInfo')
            </div>
            <div class="row">
                <div class="col-md-12 text text-center">
                    <hr style="background-color: gray; height: 2px;">
                    <b>{{strtoupper($exam->sectionClass->name)}} {{$sectionClassSubject->subject->name}} QUESTION PAPER FOR {{strtoupper($exam->academicSessionTerm->term->name)}} {{$exam->currentSession()->name}} SESSION</b><hr style="background-color: orange; height: 3px;">
                </div>
                <div class="col-md-1"></div>
                <div class="col-md-10">
                    @include('section.class.exam.include.header')
                </div>
            </div>
        </div>
        <hr>
        <div class="row h5">
            @foreach($sectionClassSubject->examSubjectQuestionSections as $questionSection)
            @if(count($questionSection->questions) >0)
            <div class="col-md-1"></div>
            <div class="col-md-11"><b>{{strtoupper($questionSection->name)}}<br>
            Instruction: <span>{{$questionSection->instruction}}</span></b></div>
                @foreach($questionSection->questions as $question)
                <div class="col-md-1 text-center">Q. {{$loop->iteration}}</div>
                <div class="col-md-10"style="page-break-inside: avoid;">{{$question->question}}</div>
                <div class="col-md-1"></div>
                @if($question->answer)
                <div class="col-md-1 text-center"></div>
                <div class="col-md-10">{{$question->answer}}</div>
                <div class="col-md-1"></div>
                @endif
                @if($question->diagram)
                <div class="col-md-1 text-center"></div>
                <div class="col-md-10"><img src="{{Storage::url($question->diagram)}}" alt="" height="180" width="90%"></div>
                <div class="col-md-1"></div>
                @endif
                @if(count($question->questionItems) > 0)
                    <div class="col-md-1 text-right"></div>
                    <div class="col-md-10">
                        <ol class="row">
                            @foreach($question->questionItems as $questionItem)
                                <li class="col-md-6">{{$questionItem->name}}</li>
                            @endforeach
                        </ol>    
                    </div>
                    <div class="col-md-1"></div>
                @endif
                <br>
                @if(count($question->options) > 0)
                    <div class="col-md-1 text-right"></div>
                    <div class="col-md-10">
                        <div class="row">
                            @foreach($question->options as $option)
                                <div class="col-md-6">{{$option->name}}. {{$option->value}}</div>
                            @endforeach
                        </div>
                    </div>
                    <div class="col-md-1"></div>
                @endif
                
            @endforeach
            @endif
            @endforeach
        </div>  
    </div>
    <br>
    @endforeach
    </div>
    @endsection    
</x-app-layout>
