
    <input type="hidden" name="url" id="url" value="clients">
    <div class="form-group firstname">
        <label for="firstname">First Name</label><span class="required">*</span>
        <input type="text" name="firstname" class="form-control" id="firstname" value="{{$user->firstname}}">
    </div>
    <div class="form-group middlename">
        <label for="middlename">Middle Name</label>
        <input type="text" name="middlename" class="form-control" id="middlename" value="{{$user->middlename}}">
    </div>
    <div class="form-group lastname">
        <label for="lastname">Last Name</label><span class="required">*</span>
        <input type="text" name="lastname" class="form-control" id="lastname" value="{{$user->lastname}}">
    </div>
    <div class="form-group birthday">
        <label for="birthday">Date Of Birth</label>
        <input name="birthday" id="birthday" type="text" class="form-control" data-inputmask-alias="datetime" data-inputmask-inputformat="mm/dd/yyyy" data-mask  value="{{$user->birthday}}">
    </div>
    <div class="form-group landline">
        <label>Phone</label>
        <input name="landline" id="landline" type="text" class="form-control" data-inputmask="'mask': '999 999 999'" data-mask value="{{$user->landline}}">
    </div>
    <div class="form-group mobileNo">
        <label>Mobile</label>
        <input name="mobileNo" id="mobileNo" type="text" class="form-control" data-inputmask="'mask': '(9999) 999-9999'" data-mask value="{{$user->mobileNo}}">
    </div>


    <script>
        $('[data-mask]').inputmask();

    </script>