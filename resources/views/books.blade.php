@extends('layouts.guest')
@section('content')
    <div class="containers">
        <div class="row">
            @foreach ($books as $book)
            <div class="col-md-3">
                <div style="border:2px dashed orange; text-align:center; color: green" class="p-2 mb-2">
                    <div class="row">
                        <div class="col-md-4">{{App\Models\Section::find(1)->generateQrCode($book['notice'], 80)}}</div>
                        <div class="col-md-8"><h4>FAYIS</h4><h5>{{( $book['number'])}}</h5></div>
                    </div>
                 
                </div>
            </div>
            @endforeach
        </div>
    </div>
@endsection