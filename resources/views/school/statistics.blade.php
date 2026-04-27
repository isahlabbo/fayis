
<table>
    <thead>
    <tr>
        <th>S/N</th>
        <th>Class Teacher Name</th>
        <th>Class Teacher Phone Number</th>
        <th>Class</th>
        <th>Total Students</th>
        <th>Number of Boys</th>
        <th>Number of Girls</th>
        <th>Average Score (%)</th>
        <th>Highest Score (%)</th>
        <th>Lowest Score (%)</th>
        <th>Number of Subjects (%)</th>
    </tr>
    </thead>
    <tbody>
        @foreach(App\Models\SectionClass::all() as $sectionClass)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $sectionClass->currentClassMaster()->teacher->user->name ?? ''}}</td>
                <td>{{ $sectionClass->currentClassMaster()->teacher->phone ?? ''}}</td>

                <td>{{ $sectionClass->name ?? ''}}</td>
                <td>{{$sectionClass->sectionClassStudents->where('status', 'Active')->count() ?? 0}}</td>
                <td>{{$sectionClass->activeMaleStudents() ?? 0}}</td>
                <td>{{$sectionClass->activeFemaleStudents() ?? 0}}</td>
                <td>{{$sectionClass->termlyAverageScore($academicSessionTerm) ?? 0}}</td>
                <td>{{$sectionClass->termlyHighestStudentAverage($academicSessionTerm) ?? 0}}</td>
                <td>{{$sectionClass->termlyLowestStudentAverage($academicSessionTerm) ?? 0}}</td>
                <td>{{$sectionClass->sectionClassSubjects->where('status', 'Active')->count() ?? 0}}</td>
            </tr>
        @endforeach
    </tbody>
</table>