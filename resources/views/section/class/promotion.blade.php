<x-app-layout>
    @section('title')
        {{config('app.name')}}student promotion
    @endsection
    @section('breadcrumb')
       
    @endsection
    @section('content')
        @foreach(App\Models\Section::all() as $section)
        <h4 class="text text-warning text-center">{{$section->name}} Class Student Promotion</h4 class="text text-warning text-center">
        <table class="table">
            <thead>
                <tr>
                    <th>S/N</th>
                    <th>Class</th>
                    <th>Students</th>
                    <th>Promoted</th>
                    <th>Repeated</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($section->sectionClasses as $sectionClass)
                    @if(count($sectionClass->sectionClassStudents->where('status', 'Active')) > 0 &&count($sectionClass->subjectResultUploads()['awaiting']) < 1)
                        <tr>
                            <td>{{$loop->iteration}}</td>
                            <td>{{$sectionClass->name}}</td>
                            <td>{{count($sectionClass->sectionClassStudents->where('status', 'Active'))}}</td>
                            <td>{{count($sectionClass->sectionClassStudents->where('status', 'Active'))-count($sectionClass->sectionClassStudents->where('status', 'Repeat'))}}</td>
                            <td>{{count($sectionClass->sectionClassStudents->where('status', 'Repeat'))}}</td>
                            <td><a href="{{route('dashboard.section.promotion.class',[$sectionClass->id])}}">
                            <button class="btn btn-primary">Make Promotion</button></a>
                            </td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
            </table>
        @endforeach
    @endsection
    
</x-app-layout>
