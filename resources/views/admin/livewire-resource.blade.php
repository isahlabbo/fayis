@extends('layouts.app')
@section('title', ucwords(str_replace('-', ' ', $resource)))
@section('content') @livewire('admin.resource-manager', ['resource' => $resource]) @endsection
