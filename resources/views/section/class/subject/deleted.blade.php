<x-app-layout>
    @section('title')
        {{$sectionClass->name}} deletedsubjects
    @endsection
    @section('breadcrumb')
       
    @endsection
    @section('content')
    <h4>{{$sectionClass->name}} Deleted Subjects</h4>
        <table class="table">
        <thead>
            <tr>
                <th>S/N</th>
                <th>SUBJECTS</th>
                <th></th>
                
            </tr>
        </thead>
        <tbody>
            @foreach($sectionClass->sectionClassSubjects->where('status', 'Inactive') as $sectionClassSubject)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$sectionClassSubject->subject->name}}</td>
                    <td>
                    
                    <a href="{{route('section.class.subject.activate',[$sectionClassSubject->id])}}" onclick="return confirm('are sure, you want to activate this subject')"><button class="btn btn-outline-warning">Activate</button></a>    
                    
                    </td>
                    
                </tr>
                
            @endforeach
        </tbody>
        </table>
    @endsection
    
</x-app-layout>
