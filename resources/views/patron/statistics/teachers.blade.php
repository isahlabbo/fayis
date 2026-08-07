@extends('layouts.app')

@section('title')
    Teacher Statistics
@endsection

@section('breadcrumb')
    {{ Breadcrumbs::render('dashboard') }}
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Teacher Statistics</h5>
            </div>
            <div class="card-body">
                @livewire('patron.statistics.teachers')
            </div>
        </div>
    </div>
@endsection
