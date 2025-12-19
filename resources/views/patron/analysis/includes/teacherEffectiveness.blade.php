<h2>Teacher Effectiveness Index</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Section</th>
                <th>Teacher</th>
                <th>Students</th>
                <th>Subjects</th>
                <th>Classes</th>
                <th>Total Obtained</th>
                <th>Total Possible</th>
                <th>Effectiveness (%)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $row)
            <tr>
                <td>{{ $row->section->name ?? 'N/A' }}</td>
                <td>{{ $row->teacher->user->name ?? 'N/A' }}</td>
                <td>{{ $row->total_students }}</td>
                <td>{{ $row->total_subjects }}</td>
                <td>{{ $row->total_classes }}</td>
                <td>{{ $row->total_obtained }}</td>
                <td>{{ $row->total_possible }}</td>
                <td>{{ $row->effectiveness_index }}%</td>
            </tr>
            @endforeach
        </tbody>
    </table>
