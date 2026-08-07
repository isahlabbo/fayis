@extends('layouts.app')

@section('title')
    Student Statistics
@endsection

@section('breadcrumb')
    {{ Breadcrumbs::render('dashboard') }}
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Student Statistics</h5>
            </div>
            <div class="card-body">
                @livewire('patron.statistics.students')
            </div>
        </div>
    </div>
@endsection
