<div class="container-fluid py-3">
    <h3>Students</h3>
    @if(session('success'))<div class="alert alert-success" role="status">{{ session('success') }}</div>@endif
    <p class="text-muted">Statistics reflect the current filters. Each student appears once, using their latest matching enrolment.</p>
    <div class="row mb-3" aria-live="polite">
        @foreach(['total' => 'Total students', 'male' => 'Male', 'female' => 'Female'] as $key => $label)
            <div class="col-md-4 mb-2"><div class="card shadow-sm h-100"><div class="card-body">
                <div class="text-muted">{{ $label }}</div><strong class="h3">{{ number_format($statistics[$key]) }}</strong>
            </div></div></div>
        @endforeach
    </div>
    @if($statistics['unspecified'])<p class="text-muted">{{ $statistics['unspecified'] }} student(s) have an unspecified gender and are included in the total.</p>@endif
    <div class="card shadow-sm"><div class="card-body">
        <div class="row">
            <div class="col-md-4 form-group"><label for="student-search">Search student</label><input id="student-search" wire:model.debounce.300ms="search" class="form-control" placeholder="Name or admission number"></div>
            <div class="col-md-4 form-group"><label for="student-session">Academic session</label><select id="student-session" wire:model="sessionId" class="form-control"><option value="">All sessions</option>@foreach($sessions as $session)<option value="{{ $session->id }}">{{ $session->name }}</option>@endforeach</select></div>
            <div class="col-md-4 form-group"><label for="student-status">Enrolment status</label><select id="student-status" wire:model="status" class="form-control"><option value="">All statuses</option>@foreach($statuses as $value)<option value="{{ $value }}">{{ $value }}</option>@endforeach</select></div>
            <div class="col-md-4 form-group"><label for="student-section">Section</label><select id="student-section" wire:model="sectionId" class="form-control"><option value="">All sections</option>@foreach($sections as $section)<option value="{{ $section->id }}">{{ $section->name }}</option>@endforeach</select></div>
            <div class="col-md-4 form-group"><label for="student-class">Class</label><select id="student-class" wire:model="classId" class="form-control"><option value="">All classes</option>@foreach($classes as $class)<option value="{{ $class->id }}">{{ $class->name }}</option>@endforeach</select></div>
        </div>
        <div class="d-flex align-items-center justify-content-between mb-3">
            <span aria-live="polite">{{ count($selected) }} of {{ $statistics['total'] }} students selected</span>
            @if(count($selected))<button type="button" wire:click="clearSelection" class="btn btn-sm btn-outline-secondary">Clear selection</button>@endif
        </div>
        @if(count($selected))
        <form wire:submit.prevent="updateSelected" class="border rounded p-3 mb-3" wire:key="selected-students-update">
            <h5>Update selected students</h5>
            <p class="text-muted small">Choose a session, status, or both. Session changes keep the same class and preserve earlier enrolments and records.</p>
            <div class="row align-items-end">
                <div class="col-md-4 form-group">
                    <label for="bulk-session">Academic session</label>
                    <select id="bulk-session" wire:model.defer="targetSessionId" class="form-control"><option value="">Keep current session</option>@foreach($sessions as $session)<option value="{{ $session->id }}">{{ $session->name }}</option>@endforeach</select>
                    @error('targetSessionId')<small class="text-danger">{{ $message }}</small>@enderror
                </div>
                <div class="col-md-4 form-group">
                    <label for="bulk-status">Enrolment status</label>
                    <select id="bulk-status" wire:model.defer="targetStatus" class="form-control"><option value="">Keep current status</option>@foreach($statuses as $value)<option value="{{ $value }}">{{ $value }}</option>@endforeach</select>
                    @error('targetStatus')<small class="text-danger">{{ $message }}</small>@enderror
                </div>
                <div class="col-md-4 form-group"><button type="submit" class="btn btn-primary" wire:loading.attr="disabled" @if(!count($selected)) disabled @endif>Update {{ count($selected) }} selected</button></div>
            </div>
            @error('selected')<div class="text-danger">{{ $message }}</div>@enderror
            @error('selected.*')<div class="text-danger">{{ $message }}</div>@enderror
            <div class="border-top pt-3 mt-3">
                <h6>Delete selected enrolments</h6>
                <p class="text-muted small">Choose the exact class and academic session in the filters above. This deletes only the selected enrolments and their term rows. Student profiles and enrolments in other sessions stay intact. Enrolments with linked payments, results or other records cannot be deleted here.</p>
                @error('sessionId')<div class="text-danger">{{ $message }}</div>@enderror
                @error('classId')<div class="text-danger">{{ $message }}</div>@enderror
                <button type="button" class="btn btn-outline-danger" wire:click="deleteSelectedEnrolments" wire:loading.attr="disabled"
                    onclick="if (!confirm('Delete the selected class/session enrolments and their term rows? Student profiles and other enrolments will be kept.')) event.stopImmediatePropagation();"
                    @if(!$sessionId || !$classId) disabled @endif>Delete {{ count($selected) }} selected enrolment(s)</button>
            </div>
        </form>
        @endif
        <div class="table-responsive"><table class="table table-hover">
            <thead><tr><th><label class="mb-0 text-nowrap"><input type="checkbox" wire:model="selectAll" aria-label="Select all displayed students" @if($records->isEmpty()) disabled @endif> Select all</label></th><th>Admission no.</th><th>Student</th><th>Gender</th><th>Academic session</th><th>Status</th><th>Guardian</th><th>Actions</th></tr></thead>
            <tbody>
                @forelse($records as $record)
                    <tr wire:key="student-enrolment-{{ $record->id }}">
                        <td><input type="checkbox" wire:model="selected" value="{{ $record->id }}" aria-label="Select {{ $record->student->name }}"></td>
                        <td>{{ $record->student->admission_no ?: '-' }}</td><td>{{ $record->student->name }}</td>
                        <td>{{ optional($record->student->gender)->name ?: 'Unspecified' }}</td>

                        <td>{{ optional($record->academicSession)->name ?? '-' }}</td><td>{{ $record->status }}</td>
                        <td>{{ optional($record->student->guardian)->name }}<br><small>{{ optional($record->student->guardian)->phone }}</small></td>
                        <td><a href="{{ route('admission.student.edit', $record->student_id) }}" class="btn btn-sm btn-outline-primary" aria-label="Edit {{ $record->student->name }}"><i class="fas fa-edit mr-1" aria-hidden="true"></i>Edit</a></td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center text-muted">No students match the selected filters.</td></tr>
                @endforelse
            </tbody>
        </table></div>
    </div></div>
</div>
