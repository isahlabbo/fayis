<h2>Termly Class Averaging</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Section</th>
                <th>Class</th>
                <th>Students Count</th>
                <th>Subjects Count</th>
                <th>Total Obtained</th>
                <th>Total Possible</th>
                <th>Class Average (%)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $row)
            <tr>
                <td>{{ $row->section->name ?? 'N/A' }}</td>
                <td>{{ $row->sectionClass->name ?? 'N/A' }}</td>
                <td>{{ $row->students_count }}</td>
                <td>{{ $row->subjects_count }}</td>
                <td>{{ $row->total_obtained }}</td>
                <td>{{ $row->total_possible }}</td>
                <td>{{ $row->class_average }}%</td>
            </tr>
            @endforeach
        </tbody>
    </table>