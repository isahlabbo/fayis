<div class="row">
    <!-- Results -->
    <div class="col-md-3 mb-4">
        <a href="" class="text-decoration-none">
            <div class="card-body shadow text-center rounded-3">
                <h5 class="text-primary">
                    <i class="fas fa-clipboard-check"></i> Results
                </h5>
                <h6 class="text-primary">
                    <!-- Add count or data here -->
                    {{ count(App\Models\SectionClassSubjectUploads::all()) }}
                </h6>
            </div>
        </a>
    </div>

    <!-- Psychomotor -->
    <div class="col-md-3 mb-4">
        <a href="" class="text-decoration-none">
            <div class="card-body shadow text-center rounded-3">
                <h5 class="text-primary">
                    <i class="fas fa-brain"></i> Psychomotor
                </h5>
                <h6 class="text-primary">
                    {{ count(App\Models\Psychomotor::all()) }}
                </h6>
            </div>
        </a>
    </div>

    <!-- Affective Traits -->
    <div class="col-md-3 mb-4">
        <a href="" class="text-decoration-none">
            <div class="card-body shadow text-center rounded-3">
                <h5 class="text-primary">
                    <i class="fas fa-heart"></i> Affective Traits
                </h5>
                <h6 class="text-primary">
                    {{ count(App\Models\AffectiveTrait::all()) }}
                </h6>
            </div>
        </a>
    </div>

    <!-- Grade Scale -->
    <div class="col-md-3 mb-4">
        <a href="" class="text-decoration-none">
            <div class="card-body shadow text-center rounded-3">
                <h5 class="text-primary">
                    <i class="fas fa-chart-line"></i> Grade Scale
                </h5>
                <h6 class="text-primary">
                    {{ count(App\Models\GradeScale::all()) }}
                </h6>
            </div>
        </a>
    </div>

    <!-- Remarks -->
    <div class="col-md-3 mb-4">
        <a href="" class="text-decoration-none">
            <div class="card-body shadow text-center rounded-3">
                <h5 class="text-primary">
                    <i class="fas fa-comments"></i> Remarks
                </h5>
                <h6 class="text-primary">
                    {{ count(App\Models\RemarkScale::all()) }}
                </h6>
            </div>
        </a>
    </div>
</div>