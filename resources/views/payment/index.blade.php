<x-guest-layout>
    @section('title')
      payment select class
    @endsection

    @section('content')
    <div class="row">
        <div class="col-md-4"></div>
        <div class="col-md-4">
            <div class="card-body shadow">
            
           
    <x-jet-authentication-card>
        <x-slot name="logo">
        <div class="text text-center"> <img src="{{asset('assets/images/logo-main.png')}}" alt=""></div>
        <h4 class="text text-center text-primary">Follow these few steps to make payment</h4>
        </x-slot>

        <x-jet-validation-errors style="color: red;" />

        @if (session('status'))
            <div class="text text-primary">
                {{ session('status') }}
            </div>
        @endif
        <form method="POST" action="{{ route('payment.verify') }}">
            @csrf

            <div>
                <label for="section" >Section</label>
                <select id="section"  class="form-control"  name="section">
                <option value="">Select Section</option>
                @foreach(App\Models\Section::all() as $section)
                    <option value="{{$section->id}}">{{$section->name}}</option>
                @endforeach
                </select>
            </div>

            <div>
                <label for="class" >Class</label>
                <select id="class"  class="form-control"  name="class">
                    <option value="">Select Class</option>
                </select>
            </div>

            <div>
                <label for="type" >Payment Type</label>
                <select id="type"  class="form-control"  name="type">
                    <option value="">Select Payment</option>
                    <option value="1">School Fees</option>
                    <option value="2">Items Fees</option>
                    <option value="3">NECO Registration Fees</option>
                    <option value="4">WAEC Registration Fees</option>
                    <option value="5">JAMB Registration Fees</option>
                </select>
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
    </div>
        </div>
    </div>
    @endsection
</x-guest-layout>
