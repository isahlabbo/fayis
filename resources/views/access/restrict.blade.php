<x-guest-layout>
    @section('title')
        login
    @endsection
    @section('content')
    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-8">
        <div class="card-body shadow">
        
        
    <x-jet-authentication-card>
        <x-slot name="logo">
        <div class="text text-center"> <img src="{{asset('images/logo.jpg')}}" alt="" width="100" height="100"></div>
        </x-slot>

        <x-jet-validation-errors class="mb-4" />

        @if (session('status'))
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ session('status') }}
            </div>
        @endif

        <h4 class="text text-danger">Sorry {{Auth::user()->name ?? 'Guest'}}, You dont have access to this resources on this platform</h4>
    </x-jet-authentication-card>
    </div>
    </div>
    </div>
    @endsection
</x-guest-layout>
