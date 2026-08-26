<div class="container-fluid py-3">
    <div class="d-flex flex-wrap justify-content-between align-items-start mb-3">
        <div><h3 class="mb-1">Student Promotions</h3><p class="text-muted mb-0">Move selected students to another section and class, or reverse a saved promotion.</p></div>
        <div class="btn-group mt-2 mt-md-0" role="group" aria-label="Promotion action">
            <button type="button" wire:click="$set('mode', 'promote')" class="btn {{ $mode === 'promote' ? 'btn-primary' : 'btn-outline-primary' }}">Promote students</button>
            <button type="button" wire:click="$set('mode', 'cancel')" class="btn {{ $mode === 'cancel' ? 'btn-danger' : 'btn-outline-danger' }}">Cancel promotions</button>
        </div>
    </div>

    @if(session()->has('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    @error('selected')<div class="alert alert-danger">{{ $message }}</div>@enderror

    <div class="card shadow-sm mb-3"><div class="card-body">
        <div class="row">
            <div class="col-lg-6 border-right">
                <h6 class="text-uppercase text-muted">Promote from</h6>
                <div class="row">
                    <div class="col-md-6 form-group"><label>Section</label><select wire:model="fromSectionId" class="form-control"><option value="">Select section</option>@foreach($sections as $section)<option value="{{ $section->id }}">{{ $section->name }}</option>@endforeach</select>@error('fromSectionId')<small class="text-danger">{{ $message }}</small>@enderror</div>
                    <div class="col-md-6 form-group"><label>Class</label><select wire:model="fromClassId" class="form-control" @if(!$fromSectionId) disabled @endif><option value="">Select class</option>@foreach($fromClasses as $class)<option value="{{ $class->id }}">{{ $class->name }}</option>@endforeach</select>@error('fromClassId')<small class="text-danger">{{ $message }}</small>@enderror</div>
                </div>
            </div>
            <div class="col-lg-6 pl-lg-4">
                <h6 class="text-uppercase text-muted">Promote to</h6>
                <div class="row">
                    <div class="col-md-6 form-group"><label>Section</label><select wire:model="toSectionId" class="form-control"><option value="">Select section</option>@foreach($sections as $section)<option value="{{ $section->id }}">{{ $section->name }}</option>@endforeach</select>@error('toSectionId')<small class="text-danger">{{ $message }}</small>@enderror</div>
                    <div class="col-md-6 form-group"><label>Class</label><select wire:model="toClassId" class="form-control" @if(!$toSectionId) disabled @endif><option value="">Select class</option>@foreach($toClasses as $class)<option value="{{ $class->id }}">{{ $class->name }}</option>@endforeach</select>@error('toClassId')<small class="text-danger">{{ $message }}</small>@enderror</div>
                </div>
            </div>
        </div>
    </div></div>

    <div class="card shadow-sm"><div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div><strong>{{ $mode === 'cancel' ? 'Saved promotions' : 'Students' }}</strong><br><small class="text-muted">{{ $fromClassId && $toClassId ? $records->count().' record(s) available' : 'Select both source and destination classes' }}</small></div>
            <button type="button" wire:click="{{ $mode === 'cancel' ? 'cancelPromotion' : 'savePromotion' }}" onclick="confirm('{{ $mode === 'cancel' ? 'Cancel the selected promotions and return these students to their former class?' : 'Promote the selected students?' }}') || event.stopImmediatePropagation()" class="btn {{ $mode === 'cancel' ? 'btn-danger' : 'btn-success' }}" @if(!count($selected)) disabled @endif wire:loading.attr="disabled">
                <span wire:loading.remove>{{ $mode === 'cancel' ? 'Cancel selected promotion' : 'Save promotion' }} ({{ count($selected) }})</span><span wire:loading>Saving...</span>
            </button>
        </div>
        <div class="table-responsive"><table class="table table-hover">
            <thead><tr><th style="width:45px"><input type="checkbox" wire:model="selectAll" aria-label="Select all students" @if(!$fromClassId || !$toClassId || !$records->count()) disabled @endif></th><th>Admission no.</th><th>Student</th><th>{{ $mode === 'cancel' ? 'Promoted on' : 'Current class' }}</th></tr></thead>
            <tbody>@forelse($records as $record) @php($student = $mode === 'cancel' ? $record->student : $record->student)<tr wire:key="promotion-{{ $mode }}-{{ $record->id }}"><td><input type="checkbox" wire:model="selected" value="{{ $record->id }}" aria-label="Select {{ $student->name }}"></td><td>{{ $student->admission_no ?: '-' }}</td><td>{{ $student->name }}</td><td>{{ $mode === 'cancel' ? $record->created_at->format('d M Y, H:i') : optional($record->sectionClass)->name }}</td></tr>@empty<tr><td colspan="4" class="text-center text-muted py-5">{{ $fromClassId && $toClassId ? ($mode === 'cancel' ? 'No active promotions match this route.' : 'No active students found in the selected source class.') : 'Choose the “promote from” and “promote to” classes to load students.' }}</td></tr>@endforelse</tbody>
        </table></div>
    </div></div>
</div>
