<div class="container-fluid py-3"><h3>Admissions</h3><p class="text-muted">Approve pending applications and assign students to their final class.</p>
@if(session()->has('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    <div class="card shadow-sm mb-3"><div class="card-body">
        <div class="row">
            <div class="col-md-3 form-group"><label for="admission-search">Search</label><input id="admission-search" wire:model.debounce.300ms="search" class="form-control" placeholder="Student or guardian phone"></div>
            <div class="col-md-3 form-group"><label for="admission-session">Academic session</label><select id="admission-session" wire:model="filterSessionId" class="form-control"><option value="">All sessions</option>@foreach($sessions as $session)<option value="{{ $session->id }}">{{ $session->name }}</option>@endforeach</select></div>
            <div class="col-md-3 form-group"><label for="admission-section">Section</label><select id="admission-section" wire:model="filterSectionId" class="form-control"><option value="">All sections</option>@foreach($sections as $section)<option value="{{ $section->id }}">{{ $section->name }}</option>@endforeach</select></div>
            <div class="col-md-3 form-group"><label for="admission-class">Preferred class</label><select id="admission-class" wire:model="filterClassId" class="form-control"><option value="">All classes</option>@foreach($filterClasses as $class)<option value="{{ $class->id }}">{{ $class->name }}</option>@endforeach</select></div>
        </div>
        <button type="button" wire:click="resetFilters" class="btn btn-sm btn-outline-secondary">Reset filters</button>
    </div></div>
    <p class="text-muted">Counts reflect the filtered pending applications with a valid preferred class.</p>
    <div class="row mb-3" aria-live="polite">
        @foreach(['total' => 'Total applications', 'male' => 'Male', 'female' => 'Female'] as $key => $label)
            <div class="col-md-4 mb-2"><div class="card h-100 shadow-sm"><div class="card-body"><small class="text-muted">{{ $label }}</small><h3 class="mb-0">{{ number_format($statistics[$key]) }}</h3></div></div></div>
        @endforeach
    </div>
    @if($statistics['unspecified'])<p class="text-muted">{{ $statistics['unspecified'] }} application(s) have an unspecified gender and are included in the total.</p>@endif
@if($studentId)<div class="card shadow-sm mb-3"><div class="card-body"><h5>Approve application</h5><div class="row align-items-end"><div class="col-md-8 form-group mb-md-0"><label>Assign class</label><select wire:model.defer="classId" class="form-control"><option value="">Select class</option>@foreach($classes as $class)<option value="{{ $class->id }}">{{ optional($class->section)->name }} — {{ $class->name }}</option>@endforeach</select>@error('classId')<small class="text-danger">{{ $message }}</small>@enderror</div><div class="col-md-4"><button wire:click="approve" class="btn btn-success">Approve & assign</button> <button wire:click="cancel" class="btn btn-light">Cancel</button></div></div></div></div>@endif
<div class="card shadow-sm"><div class="card-body"><div class="table-responsive"><table class="table table-hover"><thead><tr><th>Applicant</th><th>Guardian</th><th>Preferred class</th><th></th></tr></thead><tbody>@forelse($applications as $application)<tr><td>{{ $application->name }}</td><td>{{ optional($application->guardian)->name }}<br><small>{{ optional($application->guardian)->phone }}</small></td><td>{{ optional(optional($application->desiredSectionClass)->section)->name }} / {{ optional($application->desiredSectionClass)->name }}</td><td><button wire:click="select({{ $application->id }})" class="btn btn-sm btn-success">Review admission</button></td></tr>@empty<tr><td colspan="4" class="text-center text-muted">No pending applications match the selected filters.</td></tr>@endforelse</tbody></table></div></div></div></div>
