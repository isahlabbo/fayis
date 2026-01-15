@extends('layouts.app')
@section('title')
    {{$classTeacher->sectionClass->name}} - Class Dashboard
@endsection

@section('breadcrumb')
@endsection 
@section('content')
<div class="row mt-4">
    <!-- Students -->
    <div class="col-md-3 mb-4">
        <a href="{{route('teacher.class.student.index',[$classTeacher->sectionClass->id])}}" class="text-decoration-none">
            <div class="card-body shadow text-center rounded-3">
                <h5 class="text-primary">
                    <i class="fas fa-user-graduate"></i> Students
                </h5>
                <h6 class="text-muted">View student records and profiles</h6>
            </div>
        </a>
    </div>

    <!-- Report Card -->
    <div class="col-md-3 mb-4">
        <a href="{{route('teacher.class.result.index',[$classTeacher->sectionClass->id])}}" class="text-decoration-none">
            <div class="card-body shadow text-center rounded-3">
                <h5 class="text-primary">
                    <i class="fas fa-clipboard-check"></i> Submitted Results
                </h5>
                <h6 class="text-muted">View and verify submitted results</h6>
            </div>
        </a>
    </div>

    <!-- Assessment -->
    <div class="col-md-3 mb-4">
        <a href="{{route('teacher.class.assessment.index',[$classTeacher->id])}}" class="text-decoration-none">
            <div class="card-body shadow text-center rounded-3">
                <h5 class="text-primary">
                    <i class="fas fa-chart-bar"></i> Assessment
                </h5>
                <h6 class="text-muted">Assess Psychomotic and Affective Traits of students</h6>
            </div>
        </a>
    </div>
    <!-- class subject and teachers -->
    <div class="col-md-3 mb-4">
        <a href="{{route('teacher.class.subjects',[$classTeacher->sectionClass->id])}}" class="text-decoration-none">
            <div class="card-body shadow text-center rounded-3">
                <h5 class="text-primary">
                    <i class="fas fa-book"></i> Class Subjects & Teachers
                </h5>
                <h6 class="text-muted">View subjects and assigned teachers for the class</h6>   
                </div>
        </a>
    </div>
    
</div>

@endsection