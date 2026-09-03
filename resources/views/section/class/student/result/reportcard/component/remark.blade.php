<table style="width: 100%;">
    <tbody>
        <tr>
            <td>CLASS MASTER'S REMARK: </td>
            <td>{{$sectionClassStudentTerm->sectionClassStudentTermAccessment->teacherComment->name ?? 0}}</td>
        </tr>
        <tr>
            <td style="width: 300px;">HEAD OF SCHOOL REMARKS:</td>
            <td>{{$sectionClassStudentTerm->sectionClassStudentTermAccessment->headTeacherComment->name ?? 0}}</td>
        </tr>
    </tbody>
</table>
