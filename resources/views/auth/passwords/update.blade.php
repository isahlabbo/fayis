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
        <h5 class="text-primary" style="text-align: center;">Change Your Password for Security Reasons</h5>
        </x-slot>

        <x-jet-validation-errors class="mb-4" />

        @if (session('status'))
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.update') }}">
            @csrf

            
            <div class="mt-2">
                <x-jet-label for="password" value="{{ __('New Password') }}" />
                <x-jet-input id="password" class="form-control" type="password" name="password" required autocomplete="current-password" />
            </div>

            <div class="mt-2">
                <x-jet-label for="password" value="{{ __('Confirm New Password') }}" />
                <x-jet-input id="password" class="form-control" type="password" name="password_confirmation" required autocomplete="current-password" />
            </div>

            <div class="flex items-center justify-end mt-4">
                
                <x-jet-button class="ml-4 btn btn-primary">
                    {{ __('Update Password') }}
                </x-jet-button>
                
            </div>
        </form>
    </x-jet-authentication-card>
    </div>
    </div>
    </div>
    @endsection
</x-guest-layout>
