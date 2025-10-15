<x-app-layout>
    @section('title')
        sections
    @endsection
    @section('breadcrumb')
       {{Breadcrumbs::render('dashboard')}}
    @endsection
    @section('content')
        <div class="card shadow">
            <div class="card-body">
                <div class="card-header text text-center h4 shadow">{{strtoupper(config('app.name'))}} {{$section->name}} RESULT MANAGEMENT CENTER</div>
                <table class="table">
                    <thead>
                        <tr>
                            <th>S/N</th>
                            <th>CLASS</th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($section->sectionClasses as $sectionClass)
                        
                            @if(count($sectionClass->sectionClassStudents->where('academic_session_id',$sectionClass->currentSession()->id)->where('status','Active'))>0)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>{{$sectionClass->name}}</td>
                                <td>
                                <a href="{{route('dashboard.section.class.result.summary',[$sectionClass->id,$sectionClass->currentSession()->id,$sectionClass->currentSessionTerm()->term->id])}}">
                                <button class="btn btn-primary">{{count($sectionClass->subjectResultUploads()['uploaded'])}} Result Uploaded</button></a></td>
                                <td>
                                @if(count($sectionClass->subjectResultUploads()['awaiting'])==0)
                                    @if($sectionClass->canPublishResult())
                                    <a href="{{route('dashboard.section.result.publish',[$sectionClass->id])}}">
                                    <button class="btn btn-danger">Publish Result Now</button></a>
                                    @elseif($sectionClass->currentSessionTerm()->term->id == 3)
                                    <a href="{{route('dashboard.section.class.promotion',[$sectionClass->id])}}">
                                    <button class="btn btn-danger">Make Promotion Now</button></a>
                                    @else
                                        Result Published
                                    @endif
                                @else
                                <a href="{{route('dashboard.section.result.awaiting',[$sectionClass->id])}}">
                                <button class="btn btn-primary">{{count($sectionClass->subjectResultUploads()['awaiting'])}} Result Awaiting</button></a>
                                @endif
                                </td>
                            </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endsection
    
</x-app-layout>
