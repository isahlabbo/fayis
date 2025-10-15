<fieldset >
    <legend>DECLARATION</legend>
    <p>I hereby certify that the information given above is to the best of my knowledge TRUE. I array_merge_recursive
    that the institute reserved the right to take appropiate action, in case of any misleading information 
    in the application</p>
    <br>
    <p>
        <div class="row">
            <div class="col-md-6"><b><u>{{$token->guardian->user->name}}</u></b><br>Name of Father/ Guardian</div>
            <div class="col-md-6">........... <br>Name of Applicant</div>
        </div>
    </p>
    <br>
    <p>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="signature">Upload Signature</label>
                    <input type="file" name="signature" class="form-control" id="signature">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="date">Date</label>
                    <input type="date" name="date" class="form-control" id="date">
                </div>
            </div>
        </div>
    </p>
</fieldset>