
<table style="width: 100%;" class=" table-bordered table- table-striped table-hover">
    <thead class="text text-center">
        <tr>
            <th>SUBJECT</th>
            <th>1ST CA</th>
            <th>2ND CA</th>
            <th>ASSIGN.</th>
            <th>EXAM</th>
            <th>TOTAL</th>
            <th>GRADE</th>
            <th>POSITION</th>
            <th>EFFORT</th>
            <th>REMARK</th>
            @if(config('app.exam'))
            <th>TEACHER</th>
            @endif
        </tr>
    </thead>
    <tbody class="m-0">
    @php
        $subjects = 0;
        $obtainedMarks = 0;
        $termResults = $sectionClassStudentTerm->studentResults
            ->filter(function ($result) {
                return $result->subjectTeacherTermlyUpload
                    && $result->subjectTeacherTermlyUpload->sectionClassSubjectTeacher
                    && $result->subjectTeacherTermlyUpload->sectionClassSubjectTeacher->sectionClassSubject;
            })
            ->groupBy(function ($result) {
                return $result->subjectTeacherTermlyUpload
                    ->sectionClassSubjectTeacher
                    ->section_class_subject_id;
            })
            ->map(function ($results) {
                return $results->sortByDesc(function ($result) {
                    return [(float) $result->total, $result->id];
                })->first();
            })
            ->sortBy(function ($result) {
                return $result->subjectTeacherTermlyUpload
                    ->sectionClassSubjectTeacher
                    ->sectionClassSubject
                    ->name;
            });
    @endphp
    
    @foreach($termResults as $studentResult)
        @php
            $subjects++;
            $obtainedMarks = $obtainedMarks + $studentResult->total;
        @endphp
        <tr >
            <td>{{$studentResult->subjectTeacherTermlyUpload->sectionClassSubjectTeacher->sectionClassSubject->name ?? 'Not Available'}}</td>
            <td class="text text-center">{{$studentResult->first_ca ?? 'Abs'}}</td>
            <td class="text text-center">{{$studentResult->second_ca ?? 'Abs'}}</td>
            <td class="text text-center">{{$studentResult->assignment ?? 0}}</td>
            <td class="text text-center">{{$studentResult->exam ?? 'Abs'}}</td>
            <td class="text text-center">{{$studentResult->total ?? 'Abs'}}</td>
            <td class="text text-center">{{$studentResult->grade ?? 'Abs'}}</td>
            <td class="text text-center">{{$studentResult->subjectTeacherTermlyUpload->position($studentResult->total)}}</td>
            <td class="text text-center">{{$studentResult->effort()}}</td>
            <td class="text text-center">{{$studentResult->remark()}}</td>
            @if(config('app.exam'))
            <td class="text text-center">{{$studentResult->teacher()->user->name ?? 'Not Available'}}</td>
            @endif            
        </tr>
    @endforeach
    </tbody>
</table>

<table class="table-bordered" style="width: 300px; margin-top: 4px;">
    <tbody>
    <tr>
        <td width="200"><b>Total Marks:</b></td>
        <td width="100"><b>{{number_format($subjects * 100, 0)}}</b></td>
    </tr>
    <tr>
        <td><b>Obtain Marks:</b></td>
        <td><b>{{number_format($obtainedMarks, 2)}}</b></td>
    </tr>
    </tbody>
</table>
