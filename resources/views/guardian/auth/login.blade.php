<x-guest-layout>
    @section('title')
       guardian login
    @endsection
    <x-jet-authentication-card>
        <x-slot name="logo">
        
        </x-slot>

        <x-jet-validation-errors style="color: red;" />

        @if (session('status'))
            <div class="text text-primary">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('dashboard.guardian.verify') }}">
            @csrf

            <div>
                <x-jet-label for="section" value="{{ __('Section') }}" />
                <select id="section"  class="form-control"  name="section">
                <option value="">Select Section</option>
                @foreach(App\Models\Section::all() as $section)
                    <option value="{{$section->id}}">{{$section->name}}</option>
                @endforeach
                </section>
            </div>
            <div class="flex items-center justify-end mt-4">
                
                <x-jet-button class="ml-4 btn btn-primary">
                    {{ __('Continue') }}
                </x-jet-button>
                <a href="{{url('/')}}" class="ml-4 btn btn-success">
                    {{ __('Home') }}
                </a>
            </div>
            
    </x-jet-authentication-card>
</x-guest-layout>
