<fieldset >
    <legend>Please indicate any (by clicking <i class="fas fa-check"></i>) any type of diseases a child has suffer from most recently </legend>
    <div class="row">
    @foreach(App\Models\Disease::all() as $disease)
        <div class="col-md-4 mt-2" >{{$disease->name}}: <input type="checkbox" name="diseases[{{$disease->id}}]"></div>
    @endforeach
    <div class="col-md-6">
        <div class="form-group">
            <label for="other_disease">Any other disease not mension above</label>
            <input type="text" name="other_disease" class="form-control" id="other_disease">
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="level_of_recitation">Is your ward allergic to any drug or food (<i class="fas fa-check"></i>)</label>
            Yes <input type="radio" name="confirm_allergic" value="yes">
            No <input type="radio" name="confirm_allergic" value="no">
        </div>
        <div class="form-group">
            <label for="other_disease">if yes, explain</label>
            <textarea name="food_drug_allergic" id="" cols="30" rows="3" class="form-control"></textarea>
        </div>
    </div>
    </div>
</fieldset>