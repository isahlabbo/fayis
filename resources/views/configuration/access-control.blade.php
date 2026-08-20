@extends('layouts.app')

@section('title', 'Access control')

@section('content')
    @livewire('configuration.access-control', ['activeTab' => $activeTab ?? 'roles'])
@endsection
