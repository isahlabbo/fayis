<fieldset >
<div class="row">
        <div class="col-md-6">
            
            <div class="form-group">
                <label for="applicant" class="required-label"> Name of Applicant</label>
                <input type="text" required name="name" class="form-control" id="applicant">
            </div>
            
            <div class="form-group">
                <label for="date_of_birth" class="required-label">Date of Birth</label>
                <input type="date" name="date_of_birth" class="form-control" id="date_of_birth">
            </div>
            <div class="form-group">
                <label for="place_of_birth" class="required-label">Place of Birth</label>
                <input type="text" name="place_of_birth" class="form-control" id="place_of_birth">
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="state" class="required-label">State of Origin</label>
                        <select  name="state" class="form-control" id="state">
                            <option value="">Select State</option>
                            @foreach(App\Models\State::all() as $state)
                                <option value="{{$state->id}}">{{$state->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="lga" class="required-label">LGA of Origin</label>
                        <select  name="lga" class="form-control" id="lga">
                            <option value="">Select LGA</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="picture" class="required-label">Upload Applicant Picture</label>
                <input type="file" id="picture" name="picture" class="form-control" id="picture">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="guadian_name">Name of Father/ Guardian</label>
                <input type="text" name="guardian_name" class="form-control" id="applicant" value="{{$token->guardian->user->name}}" disabled>
            </div>
            <div class="form-group">
                <label for="guardian_occupation">Occupation of Guardian</label>
                <input type="text" name="guardian_occupation" class="form-control" id="guardian_occupation" disabled value="{{$token->guardian->occupation}}">
            </div>
            <div class="form-group">
                <label for="contact_address">Contact Address</label>
                <input type="text" name="contact_address" class="form-control" id="contact_address" disabled value="{{$token->guardian->contact_address}}">
            </div>
            <div class="form-group">
                <label for="residence_address">Residence Address</label>
                <input type="text" name="residence_address" class="form-control" id="residence_address" disabled value="{{$token->guardian->residence_address}}">
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="office_phone">Office Phone</label>
                        <input type="text" name="office_phone" class="form-control" id="office_phone" disabled value="{{$token->guardian->office_phone}}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="residence_phone">Residence Phone</label>
                        <input type="text" name="residence_phone" class="form-control" id="residence_phone" disabled value="{{$token->guardian->residence_phone}}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="gsm">GSM</label>
                        <input type="text" name="gsm" class="form-control" id="gsm" disabled value="{{$token->guardian->gsm}}">
                    </div>
                </div>
            </div>
        </div>
    </div>
</fieldset>