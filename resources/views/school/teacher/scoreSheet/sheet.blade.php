<table class="table" >
    <thead>
    <tr>
        <th>S/N</th>
        <th>Name</th>
        <th>Admission No</th>
        <th>1ST CA</th>
        <th>2ND CA</th>
        <th>EXAM</th>
    </tr>
    </thead>
    <tbody>
    @if(config('app.mode') == 'TEST')
        @foreach($students as $student)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $student->name }}</td>
                <td>{{ $student->admission_no }}</td>
                <td>{{rand(1,20)}}</td>
                <td>{{rand(1,20)}}</td>
                <td>{{rand(1,60)}}</td>
            </tr>
        @endforeach
    @else
        @foreach($students as $student)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $student->name }}</td>
                <td>{{ $student->admission_no }}</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        @endforeach
    @endif
    </tbody>
</table>