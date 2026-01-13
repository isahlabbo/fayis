<table>
    <thead>
    <tr>
        <th>S/N</th>
        <th>Name</th>
        <th>Admission No</th>
        <th>Class</th>
        <th>Section</th>
    </tr>
    </thead>
    <tbody>
        @foreach(App\Models\SectionClassStudent::where('status', 'Active')->get() as $classStudent)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $classStudent->student->name }}</td>
                <td>{{ $classStudent->student->admission_no }}</td>
                <td>{{ $classStudent->sectionClass->name }}</td>
                <td>{{ $classStudent->sectionClass->section->name }}</td>
            </tr>
        @endforeach
    
    </tbody>
</table>

<table>
    <thead>
    <tr>
        <th>S/N</th>
        <th>Name</th>
        <th>Email</th>
        <th>Phone Number</th>
        <th>Address</th>
    </tr>
    </thead>
    <tbody>
        @foreach(App\Models\Teacher::all() as $teacher)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $teacher->user->name ?? ''}}</td>
                <td>{{ $teacher->user->email ?? ''}}</td>
                <td>{{ $teacher->phone ?? ''}}</td>
                <td>{{ $teacher->address ?? ''}}</td>
            </tr>
        @endforeach
    </tbody>
</table>