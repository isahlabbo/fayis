<div class="row">
    <div class="col-md-12">
        <!-- display classes averages per terms accross the session -->
        @foreach(App\Models\Section::all() as $section)
            <h4>{{$section->name}} Classes Performance Averages</h4><hr>
            <div class="row">
                @foreach($section->sectionClasses as $sectionClass)
                <div class="col-md-4 ">
                    <div class="card-body shadow m-2">
                        <h5 class="text text-center text-success">{{$sectionClass->name}}</h5>
                        <table>
                            <thead>
                                <tr>
                                    <th >TERM</th>
                                    <th>AVERAGE</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(App\Models\Term::all() as $term)
                                    <tr>
                                        <td width="50%">{{$term->name}}</td>
                                        <td>{{number_format($sectionClass->classAverage($term), 2)}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
                @endforeach
            </div>
        @endforeach  
    </div>
</div>