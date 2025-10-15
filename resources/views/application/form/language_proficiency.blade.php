<fieldset >
    <legend>Indicate your level of proficiency in the following languages</legend>
    <table class="table bordered">
    <thead>
        <tr>
            <th>LANGUAGE</th>
            <th>READING</th>
            <th>WRITEN</th>
            <th>SPOKEN</th>
        </tr>
    </thead>
    <tbody>
    @foreach(App\Models\Language::all() as $language)
        <tr>
            <th>{{$language->name}}</th>
            <th><input type="checkbox" name="language[{{$language->id}}_reading]"></th>
            <th><input type="checkbox" name="language[{{$language->id}}_writing]"></th>
            <th><input type="checkbox" name="language[{{$language->id}}_spoken]"></th>
        </tr>
    @endforeach
    </tbody>
    </table>
</fieldset>