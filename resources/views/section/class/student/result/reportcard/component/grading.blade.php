<p  class="text-center"><b>KEY TO GRADING</b></p>
<table class="table-bordered text-center" style="width: 100%; height: 20px;">
    <tbody>
        @foreach($gradeScales as $gradeScale)
        <tr>
            <td>{{$gradeScale->grade}}</td>
            <td>{{$gradeScale->from}}-{{$gradeScale->to}}</td>
        </tr>
        @endforeach
    </tbody>
</table>