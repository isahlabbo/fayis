<x-app-layout>
    @section('title')
        Dashboard
    @endsection

    @section('breadcrumb')
       {{Breadcrumbs::render('dashboard')}}
    @endsection
    @section('content')
        @if(Auth::user()->isSuperAdmin())
            @include('dashboard.superadmin')
        @elseif(Auth::user()->usesRole('admin'))
            @include('dashboard.admin')
        @elseif(Auth::user()->usesRole('head'))
            @include('dashboard.head')
        @elseif(Auth::user()->usesRole('exam_officer'))
            @include('dashboard.exam_officer')
        @elseif(Auth::user()->usesRole('admission_officer'))
            @include('dashboard.admission_officer')
        @elseif(Auth::user()->usesRole('finance_officer'))
            @include('dashboard.finance_officer')
        @elseif(Auth::user()->usesRole('patron'))
            @include('dashboard.patron')
        @elseif(Auth::user()->usesRole('teacher') && Auth::user()->teacher)
            @include('dashboard.teacher')
        @else
            @include('dashboard.account')
        @endif
    @endsection
    
</x-app-layout>
