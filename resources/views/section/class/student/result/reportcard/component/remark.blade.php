<table >
    <div class="col-md-12">
        <tr>
            <td>FORM TEACHER'S REMARKS: </td>
            <td>{{$sectionClassStudentTerm->sectionClassStudentTermAccessment->teacherComment->name ?? 0}}</td>
        </tr>
    </div>
    <div class="col-md-12">
        <tr>
            <td style="width: 300px;">HEAD TEACHER REMARKS:</td>
            <td>{{$sectionClassStudentTerm->sectionClassStudentTermAccessment->headTeacherComment->name ?? 0}}</td>
        </tr>
    </div>
</table>