<div class="container-fluid py-3">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3"><div><h3 class="mb-1">Student Applications</h3><p class="text-muted mb-0">Register applicants without enrolling them in a class.</p></div><button wire:click="create" class="btn btn-primary"><i class="fas fa-plus mr-1"></i> New application</button></div>
    @if(session()->has('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    <div class="card shadow-sm mb-3"><div class="card-body">
        <div class="row">
            <div class="col-md-3 form-group"><label for="application-search">Search</label><input id="application-search" wire:model.debounce.300ms="search" class="form-control" placeholder="Student or guardian phone"></div>
            <div class="col-md-3 form-group"><label for="application-session">Academic session</label><select id="application-session" wire:model="filterSessionId" class="form-control"><option value="">All sessions</option>@foreach($sessions as $session)<option value="{{ $session->id }}">{{ $session->name }}</option>@endforeach</select></div>
            <div class="col-md-3 form-group"><label for="application-section">Section</label><select id="application-section" wire:model="filterSectionId" class="form-control"><option value="">All sections</option>@foreach($sections as $section)<option value="{{ $section->id }}">{{ $section->name }}</option>@endforeach</select></div>
            <div class="col-md-3 form-group"><label for="application-class">Preferred class</label><select id="application-class" wire:model="filterClassId" class="form-control"><option value="">All classes</option>@foreach($filterClasses as $class)<option value="{{ $class->id }}">{{ $class->name }}</option>@endforeach</select></div>
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
    @if($showForm)<div class="card shadow-sm mb-3"><div class="card-body"><h5>{{ $studentId ? 'Edit' : 'New' }} application</h5><div class="row">
        <div class="col-md-6 form-group"><label>Student name</label><input wire:model.defer="name" class="form-control">@error('name')<small class="text-danger">{{ $message }}</small>@enderror</div>
        <div class="col-md-3 form-group"><label>Date of birth</label><input type="date" wire:model.defer="dateOfBirth" class="form-control"></div>
        <div class="col-md-3 form-group"><label>Gender</label><select wire:model.defer="genderId" class="form-control"><option value="">Select</option>@foreach(App\Models\Gender::all() as $gender)<option value="{{ $gender->id }}">{{ $gender->name }}</option>@endforeach</select></div>
        <div class="col-md-6 form-group"><label>Applying for class</label><select wire:model.defer="classId" class="form-control"><option value="">Select class</option>@foreach($classes as $class)<option value="{{ $class->id }}">{{ optional($class->section)->name }} — {{ $class->name }}</option>@endforeach</select>@error('classId')<small class="text-danger">{{ $message }}</small>@enderror</div>
        <div class="col-md-6 form-group"><label>Guardian name</label><input wire:model.defer="guardianName" class="form-control"></div>
        <div class="col-md-4 form-group"><label>Guardian phone</label><input wire:model.defer="guardianPhone" class="form-control">@error('guardianPhone')<small class="text-danger">{{ $message }}</small>@enderror</div>
        <div class="col-md-4 form-group"><label>Guardian email</label><input type="email" wire:model.defer="guardianEmail" class="form-control"></div>
        <div class="col-md-4 form-group"><label>Guardian address</label><input wire:model.defer="guardianAddress" class="form-control"></div>
    </div><button wire:click="save" class="btn btn-primary">Save application</button> <button wire:click="resetForm" class="btn btn-light">Cancel</button></div></div>@endif
    <div class="card shadow-sm"><div class="card-body"><div class="table-responsive"><table class="table table-hover"><thead><tr><th>Student</th><th>Guardian</th><th>Preferred class</th><th>Status</th><th></th></tr></thead><tbody>@forelse($applications as $application)<tr><td>{{ $application->name }}</td><td>{{ optional($application->guardian)->name }}<br><small>{{ optional($application->guardian)->phone }}</small></td><td>{{ optional(optional($application->desiredSectionClass)->section)->name }} / {{ optional($application->desiredSectionClass)->name }}</td><td><span class="badge badge-warning">Pending</span></td><td class="text-nowrap"><button wire:click="edit({{ $application->id }})" class="btn btn-sm btn-outline-primary">Edit</button> <button wire:click="delete({{ $application->id }})" onclick="return confirm('Remove this application?')" class="btn btn-sm btn-outline-danger">Delete</button></td></tr>@empty<tr><td colspan="5" class="text-center text-muted">No pending applications match the selected filters.</td></tr>@endforelse</tbody></table></div></div></div>
</div>
