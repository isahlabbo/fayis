<table>
<thead>
    <tr>
        <th>NAME</th>
        <th>ADMISSION NO</th>
        <th>DAYS SCHOOL OPEN</th>
        <th>DAYS PRESENT</th>
        <th>DAYS ABSENT</th>
        <th>CLASS TEACHER REMARK</th>
        <th>HEAD TEACHER REMARK</th>
        
        @foreach($sectionClass->section->affectiveTraits->where('status',1) as $affectiveTrait)
           <th>{{strtoupper($affectiveTrait->name)}}</th>
        @endforeach

        @foreach($sectionClass->section->psychomotors->where('status',1) as $psychomotor)
           <th>{{strtoupper($psychomotor->name)}}</th>
        @endforeach
        
    </tr>
</thead>
<tbody>
@if(config('app.mode') == 'TEST')
    @foreach($sectionClass->sectionClassStudents->where('status','Active') as $sectionClassStudent)
    <tr>
        <td>{{$sectionClassStudent->student->name}}</td>
        <td>{{$sectionClassStudent->student->admission_no}}</td>
        <td>80</td>
        <td>70</td>
        <td>10</td>
        <td>{{rand(2,46)}}</td>
        <td>{{rand(2,16)}}</td>
        @foreach($sectionClass->section->affectiveTraits->where('status',1) as $affectiveTrait)
           <td>{{rand(2,5)}}</td>
        @endforeach
        @foreach($sectionClass->section->psychomotors->where('status',1) as $psychomotor)
           <td>{{rand(2,5)}}</td>
        @endforeach
    </tr>
    @endforeach
@else
    @foreach($sectionClass->sectionClassStudents->where('status','Active') as $sectionClassStudent)
    <tr>
        <td>{{$sectionClassStudent->student->name}}</td>
        <td>{{$sectionClassStudent->student->admission_no}}</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        @foreach($sectionClass->section->affectiveTraits->where('status',1) as $affectiveTrait)
           <td></td>
        @endforeach

        @foreach($sectionClass->section->psychomotors->where('status',1) as $psychomotor)
           <td></td>
        @endforeach
    </tr>
    @endforeach
@endif    
</tbody>
</table>
