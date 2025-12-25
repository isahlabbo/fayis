<x-guest-layout>
    @section('title')
        guardian
    @endsection
    @section('content')
    @php 
        $guardian = $studentTerm->sectionClassStudent->student->guardian;
    @endphp
    <div class="row">
        <div class="col-md-4"></div>
        <div class="col-md-4">
        <div class="card-body shadow">
        
        
    <x-jet-authentication-card>
        <x-slot name="logo">
        <h6 class="text-primary" style="text-align: center;">Please confirm this guardian information, if wrongly register pls enter the correct information about this child guardian or parent</h6>
        </x-slot>

        <x-jet-validation-errors class="mb-4" />

        @if (session('status'))
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('result.guardian.update',[$guardian->id, $studentTerm->id]) }}">
            @csrf

            <div>
                <x-jet-label for="email" value="{{ __('Parent Name') }}" />
                <x-jet-input id="email" class="form-control" type="text" name="name" value="{{$guardian->name}}" required autofocus />
            </div>
            <div>
                <x-jet-label for="phone" value="{{ __('Parent Phone Number') }}" />
                <x-jet-input id="phone" class="form-control" type="phone" name="phone" value="{{$guardian->phone}}" required autofocus />
            </div>
            <div>
                <x-jet-label for="email" value="{{ __('Parent Email') }}" />
                <x-jet-input id="email" class="form-control" type="email" name="email" value="{{$guardian->email}}" required autofocus />
            </div>

            <div>
                <x-jet-label for="email" value="{{ __('Parent Address') }}" />
                <x-jet-input id="email" class="form-control" type="text" name="address" value="{{$guardian->address}}" required autofocus />
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-jet-button class="ml-4 btn btn-primary">
                    {{ __('Save Parent Information & View Result') }}
                </x-jet-button>
               
            </div>
        </form>
    </x-jet-authentication-card>
    </div>
    </div>
    </div>
    @endsection
</x-guest-layout>
