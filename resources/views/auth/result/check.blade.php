<x-guest-layout>
    @section('title')
        login
    @endsection
    @section('content')
    <div class="row">
        <div class="col-md-4"></div>
        <div class="col-md-4">
        <div class="card-body shadow">
        
        
    <x-jet-authentication-card>
        <x-slot name="logo">
        <div class="text text-center"> <img src="{{asset('images/logo.jpg')}}" alt="" width="100" height="100"></div>
        </x-slot>

        <x-jet-validation-errors class="mb-4" />

        <!-- check if there is error then display it -->
        @if (session('error'))
            <div class="mb-4 font-medium text-sm text-red-600 text text-danger">
                . {{ session('error') }}
            </div>
        @endif

        <h5 class="card-title text-center text-primary">Check Your Child's Result</h5>
        <p class="text-center">Enter your child's result reference code here to view their academic performance.</p>
        <form method="post" action="{{route('result.search')}}"class="result-form">
        @csrf  
        <div class="form-group">
            <input type="text" class="form-control" name="access_code" placeholder="Enter Reference Code" required>
            </div>
            <button class="btn btn-sm btn-outline-primary" type="submit">Check Result</button>
        </form>
    </x-jet-authentication-card>
    </div>
    </div>
    </div>
    @endsection
</x-guest-layout>
