<table>
    <thead>
        <tr>
            
            <th>Student Name</th>
            <th>Date Of Birth</th>
            <th>Admission No</th>
            <th>Gender</th>
        </tr>
    </thead>
    <tbody>
    @foreach($sectionClass->sectionClassStudents->where('status','Active') as $sectionClassStudent)
        <tr>
            
            <td>{{$sectionClassStudent->student->name}}</td>
            <td>{{$sectionClassStudent->student->date_of_birth}}</td>
            <td>{{$sectionClassStudent->student->admission_no}}</td>
            <td>{{$sectionClassStudent->student->gender()}}</td>
        </tr>
    @endforeach
    </tbody>
</table>