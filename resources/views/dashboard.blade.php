<x-app-layout>
    @section('title')
        Dashboard
    @endsection

    @section('breadcrumb')
       {{Breadcrumbs::render('dashboard')}}
    @endsection
    @section('content')
        @if(Auth::user()->role == 'admin')
            @include('dashboard.admin')
        @elseif(Auth::user()->role == 'head')
            @include('dashboard.head')
        @elseif(Auth::user()->role == 'exam_officer')
            @include('dashboard.exam_officer')
        @elseif(Auth::user()->role == 'admission_officer')
            @include('dashboard.admission_officer')
        @elseif(Auth::user()->role == 'finance_officer')
            @include('dashboard.finance_officer')
        @else
            @include('dashboard.teacher')
        @endif
    @endsection
    
</x-app-layout>
