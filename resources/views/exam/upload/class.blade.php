@extends('layouts.app')
@section('title')
    Teachers result upload report
@endsection
@section('breadcrumb')  
@endsection
@section('content')
@php 
    
@endphp
<div class="row mt-4">

    <h5 class="text text-center mb-4">Classes Result Upload Report</h5>
    @foreach(App\Models\Section::all() as $ection)
    @php 
        $sectionNotUploaded = 0;
        $sectionSubmittedToClassMaster = 0;
        $sectionSubmittedToExamOffice = 0;
        $sectionPublished = 0;  
        $sectionInProgress = 0;
        $sectionSubjects = 0;
        $sectionStudents = 0;
    @endphp

    <h6 class="text text-center mb-4">{{ $ection->name }}</h6>
    <table class="table table-sm table-bordered">
        <thead>
            <tr>
                <th>S/N</th>
                <th>Class Name</th>
                <th>Number of Student</th>
                <th>Number of Subject</th>
                <th>Submitted</th>
                <th>In Progress</th>
                <th>Not Attempted</th>
                <th>Submitted to Class Master</th>
                <th>Submitted to Exam Office</th>
                <th>Published</th>
            </tr>
        </thead>
        <tbody>
        @foreach($ection->sectionClasses as $sectionClass)
        
            @php 
                $classUploads = $sectionClass->resultUploadReport();
                $notUploaded = count($classUploads['not_uploaded']);
                $submittedToClassMaster = count($classUploads['submitted_to_class_master']);
                $submittedToExamOffice = count($classUploads['submitted_to_exam_office']);
                $published = count($classUploads['published']);  
                $inProgress = count($classUploads['in_progress']);
                $subjects = count($classUploads['subjects']);
            @endphp
            <tr class="">
                <td>{{ $loop->iteration }}</td>
                <td>{{ $sectionClass->name ?? '' }}</td>
                <td>{{ count($sectionClass->sectionClassStudents->where('status','Active')) }}</td>
                <td>{{ count($classUploads['subjects']) }}</td>
                <td>{{ count($classUploads['subjects']) - count($classUploads['not_uploaded']) }}</td>
                <td>{{ count($classUploads['in_progress']) }}</td>
                <td>{{ count($classUploads['not_uploaded']) - count($classUploads['in_progress']) }}</td>
                <td>{{ count($classUploads['submitted_to_class_master'])+count($classUploads['submitted_to_exam_office'])+  count($classUploads['published'])}}</td>
                <td>{{ count($classUploads['submitted_to_exam_office']) + count($classUploads['published'])}}</td>
                <td>{{ count($classUploads['published']) }}</td>
                <td>{{ $classUploads['remark'] }}</td>
            </tr>
            @php 
                $sectionNotUploaded += count($classUploads['not_uploaded']);
                $sectionSubmittedToClassMaster += count($classUploads['submitted_to_class_master']);
                $sectionSubmittedToExamOffice += count($classUploads['submitted_to_exam_office']);
                $sectionPublished += count($classUploads['published']);  
                $sectionInProgress += count($classUploads['in_progress']);
                $sectionSubjects += count($classUploads['subjects']);
                $sectionStudents += count($sectionClass->sectionClassStudents->where('status','Active'));
            @endphp
        @endforeach

            <tr >
                <td colspan="2"><b>Total</b></td>
                <td><b>{{ $sectionStudents }}</b></td>
                <td><b>{{ $sectionSubjects }}</b></td>
                <td><b>{{ $sectionSubjects - $sectionNotUploaded }}</b></td>
                <td><b>{{ $sectionInProgress }}</b></td>
                <td><b>{{ $sectionNotUploaded }}</b></td>
                <td><b>{{ $sectionSubmittedToClassMaster }}</b></td>
                <td><b>{{ $sectionSubmittedToExamOffice }}</b></td>
                <td><b>{{ $sectionPublished }}</b></td>
                <td>
                    @if($sectionNotUploaded + $sectionInProgress > 0)
                        <span class="badge bg-secondary lg">{{$sectionInProgress + $sectionNotUploaded}} Pending Uploads</span> 
                    @else
                        <span class="badge bg-success">All Results Uploaded</span>  
                    @endif
                </td>
            </tr>
        </tbody>
    </table>
    @endforeach
</div>
@endsection