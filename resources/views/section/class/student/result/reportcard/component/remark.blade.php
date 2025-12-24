<table >
    <div class="col-md-12">
        <tr>
            <td>CLASS MASTER'S REMARK: </td>
            <td>{{$sectionClassStudentTerm->sectionClassStudentTermAccessment->teacherComment->name ?? 0}}</td>
        </tr>
    </div>
    <div class="col-md-12">
        <tr>
            <td style="width: 300px;">HEAD OF SCHOOL REMARKS:</td>
            <td>{{$sectionClassStudentTerm->sectionClassStudentTermAccessment->headTeacherComment->name ?? 0}}</td>
        </tr>
    </div>
</table>