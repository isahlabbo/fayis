<table style="width: 100%;">
    <tbody>
        <tr>
            <td>CLASS MASTER'S REMARK: </td>
            <td>{{optional(optional($sectionClassStudentTerm->sectionClassStudentTermAccessment)->teacherComment)->name ?: 'Not provided'}}</td>
        </tr>
        <tr>
            <td style="width: 300px;">HEAD OF SCHOOL REMARKS:</td>
            <td>{{optional(optional($sectionClassStudentTerm->sectionClassStudentTermAccessment)->headTeacherComment)->name ?: 'Not provided'}}</td>
        </tr>
    </tbody>
</table>
