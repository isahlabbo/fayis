<x-app-layout>
    @section('title')
        {{$section->name}} fees
    @endsection
    
    @section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h6 class="text text-primary">Payment management for {{$section->name}}</h6>
        <div class="d-flex align-items-center gap-2">
            <select class="form-control form-control-sm" onchange="if(this.value) window.location.href=this.value">
                <option value="">Select payment menu</option>
                <option value="{{route('finance.payments.classes', [$section->id, 'school'])}}" {{ $feeType === 'school' ? 'selected' : '' }}>School Fees</option>
                <option value="{{route('finance.payments.classes', [$section->id, 'pta'])}}" {{ $feeType === 'pta' ? 'selected' : '' }}>PTA Fees</option>
                <option value="{{route('finance.payments.classes', [$section->id, 'sisco'])}}" {{ $feeType === 'sisco' ? 'selected' : '' }}>SISCO Fees</option>
                <option value="{{route('finance.fees.classes', [$section->id])}}">Fees Setting</option>
            </select>
            <div>
                <a href="{{route('finance.payments.classes', [$section->id, 'school'])}}" class="btn btn-sm btn-outline-primary {{ $feeType === 'school' ? 'active' : '' }}">School Fees</a>
                <a href="{{route('finance.payments.classes', [$section->id, 'pta'])}}" class="btn btn-sm btn-outline-primary {{ $feeType === 'pta' ? 'active' : '' }}">PTA Fees</a>
                <a href="{{route('finance.payments.classes', [$section->id, 'sisco'])}}" class="btn btn-sm btn-outline-primary {{ $feeType === 'sisco' ? 'active' : '' }}">SISCO Fees</a>
                <a href="{{route('finance.fees.classes', [$section->id])}}" class="btn btn-sm btn-outline-secondary">Fees Setting</a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            @livewire('finance.payments.summary', ['section' => $section, 'feeType' => $feeType])
        </div>
    </div>
    @endsection
</x-app-layout>
