<div>
    <div class="row g-3">
        @foreach($sections as $section)
            @php
                $studentCount = $section->sectionClasses->sum(fn($class) => $class->sectionClassStudents->where('status', 'Active')->count());
                $maleCount = $section->sectionClasses->sum(fn($class) => $class->sectionClassStudents->where('status', 'Active')->filter(fn($item) => data_get($item, 'student.gender_id') == 1)->count());
                $femaleCount = $section->sectionClasses->sum(fn($class) => $class->sectionClassStudents->where('status', 'Active')->filter(fn($item) => data_get($item, 'student.gender_id') == 2)->count());
                $classCount = $section->sectionClasses->count();
            @endphp
            <div class="col-12">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">{{ $section->name }}</h5>
                        <span class="badge badge-light">Section Overview</span>
                    </div>
                    <div class="card-body">
                        <div class="row g-4 align-items-center">
                            <div class="col-lg-7">
                                <canvas id="bar-{{ $section->id }}" height="170"></canvas>
                            </div>
                            <div class="col-lg-5">
                                <canvas id="donut-{{ $section->id }}" height="170"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const barCtx = document.getElementById('bar-{{ $section->id }}');
                    if (barCtx) {
                        new Chart(barCtx, {
                            type: 'bar',
                            data: {
                                labels: ['Classes', 'Students', 'Male', 'Female'],
                                datasets: [{
                                    label: '{{ $section->name }}',
                                    data: [{{ $classCount }}, {{ $studentCount }}, {{ $maleCount }}, {{ $femaleCount }}],
                                    backgroundColor: ['#4e73df', '#1cc88a', '#e74a3b', '#f6c23e']
                                }]
                            },
                            options: {
                                responsive: true,
                                plugins: { legend: { display: false } },
                                scales: { y: { beginAtZero: true } }
                            }
                        });
                    }

                    const donutCtx = document.getElementById('donut-{{ $section->id }}');
                    if (donutCtx) {
                        new Chart(donutCtx, {
                            type: 'doughnut',
                            data: {
                                labels: ['Male', 'Female'],
                                datasets: [{
                                    data: [{{ $maleCount }}, {{ $femaleCount }}],
                                    backgroundColor: ['#e74a3b', '#f6c23e']
                                }]
                            },
                            options: {
                                responsive: true,
                                plugins: { legend: { position: 'bottom' } }
                            }
                        });
                    }
                });
            </script>
        @endforeach
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
