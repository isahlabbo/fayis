
    
<h2>Teacher × Class × Subject Comparison</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Section</th>
                <th>Teacher</th>
                <th>Class</th>
                <th>Subject</th>
                <th>Students Count</th>
                <th>Total Obtained</th>
                <th>Total Possible</th>
                <th>Percentage (%)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $row)
            <tr>
                <td>{{ $row->section->name ?? 'N/A' }}</td>
                <td>{{ $row->teacher->user->name ?? 'N/A' }}</td>
                <td>{{ $row->sectionClass->name ?? 'N/A' }}</td>
                <td>{{ $row->subject->name ?? 'N/A' }}</td>
                <td>{{ $row->students_count }}</td>
                <td>{{ $row->total_obtained }}</td>
                <td>{{ $row->total_possible }}</td>
                <td>{{ $row->percentage }}%</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    


