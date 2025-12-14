
@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-2"></div>
    <div class="col-md-8">
      <form action="{{route('teacher.class.assessment.update',[$studentTerm->id])}}" method="post">
      @csrf
      <div class="alert alert-success">Comments</div>
      <div class="form-group">
            <label for="">Teachers Comment <span style="color:red;">*</span></label>
            <select name="teacher_comment" id="" class="form-control">
                <option value="">Select Teacher's Comment</option>
                @foreach(App\Models\TeacherComment::where('gender',$studentTerm->sectionClassStudent->student->gender_id)->get() as $teacherComment)
                <option value="{{$teacherComment->id}}">{{$teacherComment->name}}</option>
                @endforeach
            </select>
        </div>
        <!-- head of school Comments -->
        <div class="form-group">
            <label for="">Head of School Comment <span style="color:red;">*</span></label>
            <select name="head_of_school_comment" id="" class="form-control">
                <option value="">Select Head of School Comment</option>
                @foreach(App\Models\HeadTeacherComment::where('gender',$studentTerm->sectionClassStudent->student->gender_id)->get() as $headOfSchoolComment)
                <option value="{{$headOfSchoolComment->id}}">{{$headOfSchoolComment->name}}</option>
                @endforeach
            </select>
        </div>
      <div class="alert alert-success">Psychomotors Assessment</div>
        <div class="row">
            @foreach($studentTerm->sectionClassStudent->sectionClass->section->psychomotors as $psychomotor)
            <div class="col-md-3">
                <div class="form-group">
                    <label for="">{{$psychomotor->name}}</label>
                    <select name="psychomotors[{{$psychomotor->id}}]" id="psycomoto" class="form-control">
                        <option value="">Rate</option>
                        @for($i=0; $i<=5; $i++)
                        <option value="{{$i}}">{{$i}}</option>
                        @endfor
                    </select>  
                </div>
            </div>
            @endforeach
        </div>

        <div class="alert alert-success">Affective Traits Assessment</div>
        <div class="row">
            @foreach($studentTerm->sectionClassStudent->sectionClass->section->affectiveTraits as $affectiveTrait)
            <div class="col-md-3">
                <div class="form-group">
                    <label for="">{{$affectiveTrait->name}}</label>
                    <select name="traits[{{$affectiveTrait->id}}]" id="affective" class="form-control">
                        <option value="">Rate</option>
                        @for($i=0; $i<=5; $i++)
                        <option value="{{$i}}">{{$i}}</option>
                        @endfor
                    </select>
                </div>
            </div>
             @endforeach
        </div>
        
        
        <div class="form-group">
            <button class="btn btn-secondary">Submit Assessment</button>   
        </div>
        </form>
    </div>
</div>    
@endsection