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
        <h3 class="text-primary" style="text-align: center;">Login</h3>
        </x-slot>

        <x-jet-validation-errors class="mb-4" />

        @if (session('status'))
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div>
                <x-jet-label for="email" value="{{ __('Email') }}" />
                <x-jet-input id="email" class="form-control" type="email" name="email" :value="old('email')" required autofocus />
            </div>

            <div class="mt-4">
                <x-jet-label for="password" value="{{ __('Password') }}" />
                <x-jet-input id="password" class="form-control" type="password" name="password" required autocomplete="current-password" />
            </div>

            <div class="block mt-4">
                <label for="remember_me" class="flex items-center">
                    <x-jet-checkbox id="remember_me" name="remember" />
                    <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                </label>
                @if (Route::has('password.request'))
                    <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif

            </div>

            <div class="flex items-center justify-end mt-4">
                
                <x-jet-button class="ml-4 btn btn-primary">
                    {{ __('Log in') }}
                </x-jet-button>
                <a href="{{url('/')}}" class="ml-4 btn btn-success">
                    {{ __('Home') }}
                </a>
            </div>
        </form>
    </x-jet-authentication-card>
    </div>
    </div>
    </div>
    @endsection
</x-guest-layout>
