<h2>Subject Distribution Analysis</h2>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Subject</th>
            <th>Mean Score</th>
            <th>Mode Score</th>
            <th>Standard Deviation</th>
            <th>Minimum Score</th>
            <th>Maximum Score</th>
            <th>Range</th>
            <th>Variance</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $record)
        <tr>
            <td>{{ $record->subjectTeacherTermlyUpload->sectionClassSubjectTeacher->sectionClassSubject->subject->name }}</td>
            <td>{{ $record->mean }}</td>
            <td>{{ $record->mode }}</td>
            <td>{{ $record->standard_deviation }}</td>
            <td>{{ $record->min_score }}</td>
            <td>{{ $record->max_score }}</td>
            <td>{{ $record->range }}</td>
            <td>{{ $record->variance }}</td>
        </tr>
        @endforeach
    </tbody>
</table>