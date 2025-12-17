@extends('layouts.app')

@section('title')
        analysis search
@endsection
@section('breadcrumb')
    
@endsection
@section('content')
    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-6">
            <div class="card-body shadow-sm">
                <h6 class="text text-success text-center">
                    <img src="{{asset('images/logo.png')}}" alt=""><b>Give us some information here to help you with some in this school Data</b></h6>
            <form action="{{route('patron.analysis.search')}}" method="post">
                @csrf
                <div class="form-group">
                    <label for="session">Academic Session</label>
                    <select name="session" id="" class="form-control">
                        <option value="">Select Session</option>
                        @foreach(App\Models\AcademicSession::all() as $session)
                        <option value="{{$session->id}}">{{$session->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="term">Term</label>
                    <select name="term" id="" class="form-control">
                        <option value="">Select Term</option>
                        @foreach(App\Models\Term::all() as $term)
                        <option value="{{$term->id}}">{{$term->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="section">Section</label>
                    <select name="section" id="" class="form-control">
                        <option value="">Select Section</option>
                        @foreach(App\Models\Section::all() as $section)
                        <option value="{{$section->id}}">{{$section->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="analysis">Analysis</label>
                    <select name="analysis" id="" class="form-control">
                        <option value="">Select Analysis</option>
                        <option value="1">Teacher Effectiveness Index (One normalized effectiveness score per teacher)</option>
                        <option value="2">Subject vs Class Performance (Identify strong and weak subjects per class)</option>
                        <option value="3">Term vs Class Average Performance (Track class improvement or decline across terms)</option>
                        <option value="4">Teacher vs Subject–Class Performance Comparison (Compare how teachers perform across subjects and classes)</option>
                    </select>
                </div>
                <div class="form-group">
                    <button class="btn btn-primary">Submit</button>
                </div>
            </form>
            </div>
        </div>
    </div>
@endsection