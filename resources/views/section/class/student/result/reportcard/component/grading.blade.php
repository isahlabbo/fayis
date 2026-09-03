<p  class="text-center"><b>KEY TO GRADING</b></p>
<table class="table-bordered text-center report-grading" style="width: 100%; height: 20px;">
    <tbody>
        @foreach(App\Models\GradeScale::all() as $gradeScale)
        <tr>
            <td style="width: 35%;">{{$gradeScale->grade}}</td>
            <td style="width: 65%;">{{$gradeScale->from}}-{{$gradeScale->to}}</td>
        </tr>
        @endforeach
    </tbody>
</table>
