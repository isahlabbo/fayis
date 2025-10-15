
<div class="card shadow" style="page-break-inside: avoid;">
    <div class="card-body">
        <div class="row">
            @include('section.class.student.result.reportcard.component.schoolInfo')
        </div>
        <div class="row">
            <div class="col-md-12 text text-center">
                <hr style="background-color: gray; height: 2px;">
                <b>REPORT SHEET FOR {{strtoupper($sectionClassStudentTerm->currentSessionTerm()->term->name)}} {{$sectionClassStudentTerm->currentSession()->name}} ACADEMIC SESSION</b><hr style="background-color: orange; height: 3px;">
            </div>
            
            <div class="col-md-1"></div>
            @include('section.class.student.result.reportcard.component.studentInfo')
            
        </div>
        <!-- result start -->
        <div class="row">
            <div class="col-md-12">
                @include('section.class.student.result.reportcard.component.resultInfo')
            </div>
        </div>
        <!-- result end -->
        <!-- accessment start -->
        <br>
        <div class="row">

            <div class="col-md-5">
                <!-- effective trait start -->
                    @include('section.class.student.result.reportcard.component.affectiveTrait')
                <!-- effective trait end -->
            </div>

            <div class="col-md-1"></div>

            <!-- psychomotor and rest start -->
                    <div class="col-md-6">
                        @include('section.class.student.result.reportcard.component.psychomotor')
                    <div class="row">
                        <div class="col-md-6">
                            @include('section.class.student.result.reportcard.component.scale')
                        </div>
                        <br>
                        <div class="col-md-6">
                            @include('section.class.student.result.reportcard.component.grading')
                        </div>
                    </div>
                </div>
            <!-- psychomotor and rest start -->
        </div>
        <!-- accessment end -->

        <!-- remarks start -->
        <div class="row">
            @include('section.class.student.result.reportcard.component.remark')
        </div>
    </div>
</div>