<p  class="text-center"><b></b></p>
<table class="table-bordered text-center report-scale" style="width: 100%; height: 20px;">
    <thead>
        <tr>
            <th style="width: 25%;">SCALE</th>
            <th style="width: 75%;">DESCRIPTION</th>
        </tr>
    </thead>
    <tbody>
        @foreach(App\Models\RemarkScale::all() as $remarkScale)
        <tr>
            <td>{{$remarkScale->scale}}</td>
            <td>{{$remarkScale->remark}}</td>
        </tr>
        @endforeach
    </tbody>
</table>
