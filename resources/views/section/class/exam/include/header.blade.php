
<div class="row">
<div class="col-md-10 text">
    <table style="width: 100%">
        <tr>
            <td style="width: 150px"><p class="mb-0">Admission No:</p></td>
            <td><p class="mb-0 text-left"><b>{{$sectionClassStudent->student->admission_no}}</b></p></td>
        </tr>
        <tr> 
            <td> <p class="mb-0">Student Name:</p></td>
            <td><p class="mb-0 text-left"><b>{{$sectionClassStudent->student->name}}</b></p></td>
        </tr>
        
        <tr>
            <td><p class="mb-0">Class:</p></td>
            <td><p class="mb-0 text-left"><b>{{$exam->sectionClass->name ?? ''}}</b></p></td>
        </tr>
        <tr>
            <td><p class="mb-0">Term:</p></td>
            <td><p class="mb-0 text-left"><b>{{$exam->academicSessionTerm->term->name}}</b></p></td>
        </tr>
        <tr>
            <td><p class="mb-0">Subject:</p></td>
            <td><p class="mb-0 text-left"><b>{{$sectionClassSubject->subject->name}}</b></p></td>
        </tr>       
    </table>
</div>

<div class="col-md-2 text-center">
    @if($sectionClassStudent->student->picture)
    <img src="{{$sectionClassStudent->student->profileImage()}}" alt="" height="200" width="170p" class="rounded">
    @else
    <img src="{{asset('assets/images/user.jpg')}}" width="200" height="170p" class="rounded" alt="">
    @endif
</div>
</div>