<div>
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">Search</label>
                    <input type="text" wire:model.debounce.300ms="search" class="form-control" placeholder="Name, admission, guardian">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Section</label>
                    <select wire:model="selectedSection" class="form-control">
                        <option value="">All Sections</option>
                        @foreach($sections as $section)
                            <option value="{{ $section->id }}">{{ $section->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Class</label>
                    <select wire:model="selectedClass" class="form-control">
                        <option value="">All Classes</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}">{{ $class->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Gender</label>
                    <select wire:model="selectedGender" class="form-control">
                        <option value="">All Genders</option>
                        <option value="1">Male</option>
                        <option value="2">Female</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card shadow-sm border-primary">
                <div class="card-body">
                    <h6 class="text-muted">Total Students</h6>
                    <h3 class="mb-0">{{ $summary['total'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-info">
                <div class="card-body">
                    <h6 class="text-muted">Male</h6>
                    <h3 class="mb-0">{{ $summary['male'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-pink">
                <div class="card-body">
                    <h6 class="text-muted">Female</h6>
                    <h3 class="mb-0">{{ $summary['female'] }}</h3>
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
                            <th>Name</th>
                            <th>Admission No</th>
                            <th>Class</th>
                            <th>Gender</th>
                            <th>Guardian</th>
                            <th>Contact</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $record)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if(!empty($record->student->picture))
                                            <img src="{{ $record->student->profileImage() }}" alt="" class="rounded-circle mr-2" width="36" height="36">
                                        @else
                                            <div class="rounded-circle bg-light text-center mr-2" style="width:36px;height:36px;line-height:36px;">{{ strtoupper(substr($record->student->name ?? '', 0, 1)) }}</div>
                                        @endif
                                        <span>{{ $record->student->name ?? '-' }}</span>
                                    </div>
                                </td>
                                <td>{{ $record->student->admission_no ?? '-' }}</td>
                                <td>{{ $record->sectionClass->name ?? '-' }}</td>
                                <td>{{ $record->student->gender->name ?? '-' }}</td>
                                <td>{{ $record->student->guardian->name ?? '-' }}</td>
                                <td>{{ $record->student->guardian->phone ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">No active students found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
