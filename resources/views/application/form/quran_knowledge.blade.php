<fieldset >
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="level_of_recitation">Level of recitation with year of completion</label>
                <input type="text" name="level_of_recitation" class="form-control">
            </div>
            <div class="form-group">
                <label for="number_of_hizbs">Number of Hizbs (if not completed)</label>
                <input type="text" name="number_of_hizbs" class="form-control" id="number_of_hizbs">
            </div>
            <div class="form-group">
                <label for="level_of_recitation">Level of memorization (Number of Hizbs memorized)</label>
                <input type="text" name="level_of_memorization" class="form-control">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="level_of_recitation">Participation in Qur'anic Recitation</label>
                Yes <input type="radio" name="participation" value="yes">
                No <input type="radio" name="participation" value="no">
            </div>
            <table class="table bordered">
                <thead>
                    <tr>
                        <th>Level</th>
                        <th>Year</th>
                        <th>Position Held</th>
                    </tr>
                </thead>
                <tbody>
                @foreach(App\Models\RecitationParticipationLevel::all() as $level)
                <tr>
                    <td>{{$level->name}}</td>
                    <td>
                        <input type="number" name="level[{{$level->id}}_year]" class="form-control">
                    </td>
                    <td>
                        <input type="text" name="level[{{$level->id}}_position]" class="form-control">
                    </td>
                </tr>
                @endforeach 
                </tbody>
            </table>
        </div>
    </div>
</fieldset>