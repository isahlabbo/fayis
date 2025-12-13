@extends('layouts.app')

    @section('title')
        {{$section->name}} class overview
    @endsection
    @section('breadcrumb')
       
    @endsection
    @section('content')
       
        <div class="row">
            @foreach($section->sectionClasses as $sectionClass)
                <div class="col-md-3 mb-3">
                    <div class="card shadow">
                        <div class="card-body">
                            <h5><b>{{$sectionClass->name}}</b></h5>
                            <a href="{{route('patron.section.performance.index',[$sectionClass->id])}}">
                                <button class="btn btn-outline-primary">View Performance</button>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
            
    @endsection