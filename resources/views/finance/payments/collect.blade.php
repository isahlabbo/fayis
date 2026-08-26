@extends('layouts.app')
@section('title','Collect Payment')
@section('content') @livewire('finance.payments.collect',['feeId'=>$feeId]) @endsection
