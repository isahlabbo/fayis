@extends('layouts.app')
@section('title','Advance Payment')
@section('content') @livewire('finance.payments.advance',['feeId'=>$feeId]) @endsection
