<div class="container-fluid py-3">
    <div class="d-flex justify-content-between align-items-center">
        <h3>Generate Material Collection Sheet</h3>
        <a href="{{ route('admin.material.records.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-clipboard-check"></i> View Collection Records
        </a>
    </div>
    <p class="text-muted">Select a session, then a section/class to pick students. You can switch classes and keep adding students from different classes to the same collection list before generating the document.</p>

    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <h5 class="mb-1">Class to Prepare Material For</h5>
            <p class="text-muted mb-2">Choose the session and class students are being promoted into. This is the class/session printed on the sheet, useful for preparing next session's material before promotion is done.</p>
            <div class="row">
                <div class="col-md-4 form-group">
                    <label>Session to use</label>
                    <select wire:model="targetSessionId" class="form-control">
                        <option value="">Select session</option>
                        @foreach($sessions as $session)
                            <option value="{{ $session->id }}">{{ $session->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 form-group">
                    <label>Section</label>
                    <select wire:model="targetSectionId" class="form-control">
                        <option value="">Select section</option>
                        @foreach($targetSections as $section)
                            <option value="{{ $section->id }}">{{ $section->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 form-group">
                    <label>Class</label>
                    <select wire:model="targetClassId" class="form-control">
                        <option value="">Select class</option>
                        @foreach($targetClasses as $class)
                            <option value="{{ $class->id }}">{{ $class->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-7">
            <div class="card shadow-sm mb-3">
                <div class="card-body">
                    <h5 class="mb-1">Find Students</h5>
                    <p class="text-muted mb-2">Use these filters to locate students from their current section, class and session.</p>
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label>Current Session</label>
                            <select wire:model="academicSessionId" class="form-control">
                                <option value="">Select session</option>
                                @foreach($sessions as $session)
                                    <option value="{{ $session->id }}">{{ $session->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 form-group">
                            <label>Section</label>
                            <select wire:model="sectionId" class="form-control">
                                <option value="">All sections</option>
                                @foreach($sections as $section)
                                    <option value="{{ $section->id }}">{{ $section->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 form-group">
                            <label>Class</label>
                            <select wire:model="classId" class="form-control">
                                <option value="">All classes</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <input wire:model.debounce.300ms="search" class="form-control" placeholder="Search by name or admission number">
                    </div>

                    <div class="table-responsive" style="max-height: 420px; overflow-y: auto;">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>
                                        <input type="checkbox" title="Select all queried students" wire:click="toggleSelectAll"
                                            {{ $students->count() && $students->every(fn ($record) => in_array($record->id, $selected)) ? 'checked' : '' }}>
                                    </th>
                                    <th>Student</th>
                                    <th>Guardian</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($students as $record)
                                    <tr>
                                        <td>
                                            <input type="checkbox"
                                                wire:click="toggleStudent({{ $record->id }})"
                                                {{ in_array($record->id, $selected) ? 'checked' : '' }}>
                                        </td>
                                        <td>
                                            <strong>{{ $record->student->name }}</strong><br>
                                            <small class="text-muted">{{ $record->student->admission_no }} &middot; {{ optional($record->sectionClass->section)->name }} / {{ $record->sectionClass->name }}</small>
                                        </td>
                                        <td>
                                            {{ optional($record->student->guardian)->name ?? '—' }}<br>
                                            <small class="text-muted">{{ optional($record->student->guardian)->phone }}</small>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="text-center text-muted">No students match the selected filters.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-5">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h5 class="mb-0">Selected Students ({{ count($selected) }})</h5>
                        @if(count($selected))
                            <button type="button" class="btn btn-sm btn-outline-danger" wire:click="clearSelection">Clear all</button>
                        @endif
                    </div>

                    <div class="table-responsive mb-3" style="max-height: 350px; overflow-y: auto;">
                        <table class="table table-sm">
                            <tbody>
                                @forelse($selectedRecords as $record)
                                    <tr>
                                        <td>
                                            <strong>{{ $record->student->name }}</strong><br>
                                            <small class="text-muted">{{ $record->student->admission_no }} &middot; {{ optional($record->sectionClass->section)->name }} / {{ $record->sectionClass->name }}</small>
                                        </td>
                                        <td>
                                            {{ optional($record->student->guardian)->name ?? '—' }}<br>
                                            <small class="text-muted">{{ optional($record->student->guardian)->phone }}</small>
                                        </td>
                                        <td class="text-right">
                                            <button type="button" class="btn btn-sm btn-outline-danger" wire:click="removeStudent({{ $record->id }})">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td class="text-center text-muted">No student selected yet.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if(count($selected) && $targetClassId)
                        <a href="{{ route('admin.material.print', ['ids' => implode(',', $selected), 'session' => $targetSessionId, 'target_class' => $targetClassId]) }}"
                            target="_blank" class="btn btn-primary btn-block">
                            <i class="fas fa-print"></i> Generate Material Collection Sheet
                        </a>
                    @else
                        <button type="button" class="btn btn-primary btn-block" disabled>Generate Material Collection Sheet</button>
                        @if(count($selected) && !$targetClassId)
                            <small class="text-danger">Select the section and class to prepare material for, above.</small>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
