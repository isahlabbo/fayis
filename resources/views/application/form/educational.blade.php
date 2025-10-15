<fieldset >
    <legend>Schools attended with dates and certificate onbtained</legend>
    @foreach(App\Models\Certification::all() as $certification)
    <table class="table bordered">
    <thead>
        <tr>
            <th>{{strtoupper($certification->name)}}</th>
            <th>From</th>
            <th>To</th>
            <th>CERTIFICATE OBTAINED</th>
        </tr>
    </thead>
    <tbody>
        @for($i =1; $i<=3; $i++)
            <tr>
                <td><input type="text" name="school[{{$i}}{{$certification->id}}_name]" class="form-control"></td>
                <td><input type="date" name="school[{{$i}}{{$certification->id}}_from]" class="form-control"></td>
                <td><input type="date" name="school[{{$i}}{{$certification->id}}_to]" class="form-control"></td>
                <td><input type="file" name="school[{{$i}}{{$certification->id}}_certificate]" class="form-control"></td>
            </tr>   
        @endfor
    </tbody>
    </table>
    @endforeach
</fieldset>