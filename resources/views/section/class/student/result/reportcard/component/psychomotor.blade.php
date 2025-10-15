<div class="row">
    <div class="col-md-12">
        <table class="table-bordered" style="width: 100%; height: 20px;">
            <thead class="text text-center">
                <tr>
                    <th>PSYCHOMOTOR</th>
                    <th>RATING</th>
                </tr>
            </thead>
            <tbody>
            @if($sectionClassStudentTerm->sectionClassStudentTermAccessment)
            
                @foreach($sectionClassStudentTerm->sectionClassStudentTermAccessment->sectionClassStudentTermAccessmentPsychomotors as $accessmentPsychomotor)    
               @if($accessmentPsychomotor->psychomotor)
                <tr>
                    <td>{{$accessmentPsychomotor->psychomotor->name}}</td>
                    <td>{{$accessmentPsychomotor->value ?? 0}}</td>
                </tr>
                @endif
                @endforeach
            @endif
            </tbody>
        </table>
    </div>
</div>