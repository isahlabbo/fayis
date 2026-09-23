<div class="container-fluid py-3">
    <h3>Material Collection Records</h3>
    <p class="text-muted">Track which students have collected their materials, mark collections received, and generate reports of collected vs not-collected students.</p>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 form-group">
                    <label>Session</label>
                    <select wire:model="academicSessionId" class="form-control">
                        <option value="">All sessions</option>
                        @foreach($sessions as $session)
                            <option value="{{ $session->id }}">{{ $session->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 form-group">
                    <label>Section</label>
                    <select wire:model="sectionId" class="form-control">
                        <option value="">All sections</option>
                        @foreach($sections as $section)
                            <option value="{{ $section->id }}">{{ $section->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 form-group">
                    <label>Class</label>
                    <select wire:model="classId" class="form-control">
                        <option value="">All classes</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}">{{ $class->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 form-group">
                    <label>Status</label>
                    <select wire:model="status" class="form-control">
                        <option value="">All</option>
                        <option value="Pending">Not Collected</option>
                        <option value="Collected">Collected</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <input wire:model.debounce.300ms="search" class="form-control" placeholder="Search by name or admission number">
            </div>

            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="badge badge-success">Collected: {{ $collectedCount }}</span>
                    <span class="badge badge-warning">Not Collected: {{ $pendingCount }}</span>
                </div>
                <a href="{{ route('admin.material.records.report', ['session' => $academicSessionId, 'section' => $sectionId, 'class' => $classId, 'status' => $status, 'search' => $search]) }}"
                    target="_blank" class="btn btn-outline-primary btn-sm">
                    <i class="fas fa-file-alt"></i> Print Collected / Not Collected Report
                </a>
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Student</th>
                    <th>Class Preparing For</th>
                    <th>Session</th>
                    <th>Status</th>
                    <th>Receiver</th>
                    <th>Collected At</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($records as $record)
                    <tr>
                        <td>
                            <strong>{{ $record->sectionClassStudent->student->name }}</strong><br>
                            <small class="text-muted">{{ $record->sectionClassStudent->student->admission_no }}</small>
                        </td>
                        <td>{{ optional($record->targetSectionClass->section)->name }} / {{ $record->targetSectionClass->name }}</td>
                        <td>{{ optional($record->academicSession)->name ?? '—' }}</td>
                        <td>
                            @if($record->status === 'Collected')
                                <span class="badge badge-success">Collected</span>
                            @else
                                <span class="badge badge-warning">Not Collected</span>
                            @endif
                        </td>
                        <td>{{ $record->receiver_name ?? '—' }}</td>
                        <td>{{ optional($record->collected_at)->format('d M Y, h:i A') ?? '—' }}</td>
                        <td>
                            @if($record->status !== 'Collected')
                                <button type="button" class="btn btn-sm btn-outline-success" wire:click="openMarkModal({{ $record->id }})">
                                    Mark Collected
                                </button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-muted">No material collection records found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($markingId)
        <div class="modal d-block" style="background: rgba(0,0,0,.5);" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Confirm Material Collection</h5>
                        <button type="button" class="close" wire:click="cancelMark"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Name of Receiver</label>
                            <input type="text" class="form-control" wire:model.defer="receiverName" placeholder="Enter the name of the person collecting">
                            @error('receiverName') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        <p class="text-muted mb-0">This will mark all materials for this student as collected and deduct the linked items from stock.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="cancelMark">Cancel</button>
                        <button type="button" class="btn btn-success" wire:click="confirmCollection">Confirm Collection</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
