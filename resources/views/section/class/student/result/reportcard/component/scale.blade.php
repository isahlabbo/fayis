<p  class="text-center"><b></b></p>
<table class="table-bordered text-center" style="width: 100%; height: 20px;">
    <thead>
        <tr>
            <th>SCALE</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach($remarkScales as $remarkScale)
        <tr>
            <td>{{$remarkScale->scale}}</td>
            <td>{{$remarkScale->remark}}</td>
        </tr>
        @endforeach
    </tbody>
</table>