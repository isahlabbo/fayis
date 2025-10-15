<x-app-layout>
    @section('title')
        subjects upload
    @endsection
    @section('breadcrumb')
       
    @endsection
    @section('content')
        <table class="table">
            <thead>
                <tr>
                <th>Student</th>
                <th>Term</th>
                <th>Total</th>
                <th>Grade</th>
                </tr>
            </thead>
            <tbody>
                @foreach($upload->studentResults as $result)
                    <tr>
                        <td>{{$result->sectionClassStudentTerm->sectionClassStudent->student->name}}</td>
                        <td>{{$result->sectionClassStudentTerm->academicSessionTerm->term->name}}</td>                    
                        <td>{{$result->total}}</td>
                        <td>{{$result->grade}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endsection
    
</x-app-layout>
