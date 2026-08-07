<div>
    <div class="row g-3">
        @foreach($sections as $section)
            <div class="col-12">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">{{ $section->name }}</h5>
                        <span class="badge badge-light">Section Summary</span>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-2">
                                <div class="card border-primary">
                                    <div class="card-body text-center">
                                        <h6 class="text-muted">Classes</h6>
                                        <h3 class="mb-0">{{ $section->sectionClasses->count() }}</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="card border-success">
                                    <div class="card-body text-center">
                                        <h6 class="text-muted">Students</h6>
                                        <h3 class="mb-0">{{ $section->sectionClasses->sum(fn($class) => $class->sectionClassStudents->where('status', 'Active')->count()) }}</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="card border-info">
                                    <div class="card-body text-center">
                                        <h6 class="text-muted">Subjects</h6>
                                        <h3 class="mb-0">{{ $section->sectionClasses->sum(fn($class) => $class->sectionClassSubjects->where('status', 'Active')->count()) }}</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="card border-warning">
                                    <div class="card-body text-center">
                                        <h6 class="text-muted">Teachers</h6>
                                        <h3 class="mb-0">{{ $section->sectionClasses->sum(fn($class) => $class->sectionClassTeachers->where('status', 'Active')->count()) }}</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="card border-danger">
                                    <div class="card-body text-center">
                                        <h6 class="text-muted">Male</h6>
                                        <h3 class="mb-0">{{ $section->sectionClasses->sum(fn($class) => $class->sectionClassStudents->where('status', 'Active')->filter(fn($item) => data_get($item, 'student.gender_id') == 1)->count()) }}</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="card border-pink">
                                    <div class="card-body text-center">
                                        <h6 class="text-muted">Female</h6>
                                        <h3 class="mb-0">{{ $section->sectionClasses->sum(fn($class) => $class->sectionClassStudents->where('status', 'Active')->filter(fn($item) => data_get($item, 'student.gender_id') == 2)->count()) }}</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
