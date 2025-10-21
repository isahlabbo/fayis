@extends('layouts.app')
@section('title')
    Classes
@endsection

@section('breadcrumb')
@endsection 
@section('content')
<div class="row mt-4">
    <!-- Students -->
    <div class="col-md-3 mb-4">
        <a href="" class="text-decoration-none">
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
        <a href="" class="text-decoration-none">
            <div class="card-body shadow text-center rounded-3">
                <h5 class="text-primary">
                    <i class="fas fa-clipboard-check"></i> Report Card
                </h5>
                <h6 class="text-muted">View and print student report cards</h6>
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
    <!-- Attendance -->
    <div class="col-md-3 mb-4">
        <a href="{{route('teacher.class.attendance.index',[$classTeacher->sectionClass->id])}}" class="text-decoration-none">
            <div class="card-body shadow text-center rounded-3">
                <h5 class="text-primary">
                    <i class="fas fa-user-check"></i> Attendance
                </h5>
                <h6 class="text-muted">Manage student attendance records</h6>
            </div>
        </a>
    </div>
</div>

@endsection