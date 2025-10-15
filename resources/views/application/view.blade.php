@extends('layouts.app')

@section('title')
    {{$application->token->pin}} application view
@endsection

@section('content')
<div class="card-body shadow">
    <p>
        <h5 class="text-primary">PERSONAL RECORDS</h5>
    </p>
    <p>
        <h5 class="text-primary">EEDUCATIONAL RECORDS</h5>
    </p>
    <p>
        <h5 class="text-primary">KNOWLEDGE OF THE GLORIOUS QUR'AN</h5>
    </p>
    <p>
        <h5 class="text-primary">PROFICIENCY IN LANGUAGES</h5>
    </p>
    <p>
        <h5 class="text-primary">OTHER MEDICAL RECORDS</h5>
    </p>
    <p>
        <h5 class="text-primary">DECLARATION</h5>
    </p>
    @if($application->interview)
    <button class="btn btn-primary"><i class="fas fa-print"></i>Print Admission Letter</button>
    @endif
    <button class="btn btn-info"><i class="fas fa-info"></i>Add Iterview</button>
   
</div>
@endsection