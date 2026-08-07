<div>
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="row g-3 align-items-end">
                <div class="col-md-6">
                    <label class="form-label">Search</label>
                    <input type="text" wire:model.debounce.300ms="search" class="form-control" placeholder="Teacher name, email or phone">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Section</label>
                    <select wire:model="selectedSection" class="form-control">
                        <option value="">All Sections</option>
                        @foreach($sections as $section)
                            <option value="{{ $section->id }}">{{ $section->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Teacher</th>
                            <th>Contact</th>
                            <th>Classes</th>
                            <th>Subjects</th>
                            <th>Profile</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($teachers as $teacher)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($teacher->profileImage())
                                            <img src="{{ $teacher->profileImage() }}" alt="" class="rounded-circle mr-2" width="40" height="40">
                                        @else
                                            <div class="rounded-circle bg-light text-center mr-2" style="width:40px;height:40px;line-height:40px;">{{ strtoupper(substr($teacher->user->name ?? '', 0, 1)) }}</div>
                                        @endif
                                        <div>
                                            <div class="fw-bold">{{ $teacher->user->name ?? '-' }}</div>
                                            <small class="text-muted">{{ $teacher->user->email ?? 'N/A' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div>{{ $teacher->user->email ?? '-' }}</div>
                                    <small class="text-muted">{{ $teacher->phone ?? '-' }}</small>
                                </td>
                                <td>
                                    @php $classes = $teacher->sectionClassTeachers->where('status', 'Active')->pluck('sectionClass.name')->filter()->unique(); @endphp
                                    @if($classes->isNotEmpty())
                                        <span class="badge badge-info">{{ $classes->count() }}</span> {{ $classes->implode(', ') }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @php $subjects = $teacher->sectionClassSubjectTeachers->where('status', 'Active')->pluck('sectionClassSubject.subject.name')->filter()->unique(); @endphp
                                    @if($subjects->isNotEmpty())
                                        <span class="badge badge-success">{{ $subjects->count() }}</span> {{ $subjects->implode(', ') }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    <small>
                                        @if(!empty($teacher->date_of_appointment))
                                            Appointed: {{ $teacher->date_of_appointment }}<br>
                                        @endif
                                        @if(!empty($teacher->appointment_grade_level))
                                            Grade: {{ $teacher->appointment_grade_level }}
                                        @endif
                                    </small>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">No teachers found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
