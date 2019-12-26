<div class="form-group address">
    <label for="address">Address</label><span class="required">*</span>
    <input type="text" name="address" class="form-control" id="address" value="{{$user->address}}">
</div>
<div class="form-group region">
    <label for="region">Region</label><span class="required">*</span>
    <select name="region" id="region" class="form-control phil-region" style="width: 100%;">
        @foreach($regions as $region)
            <option value="{{$region->regCode}}" @if($region->regCode === $user->refregion) selected="selected" @endif>{{$region->regDesc}}</option>
        @endforeach
    </select>
</div>
<div class="form-group state">
    <label for="state">State</label><span class="required">*</span>
    <select name="state" class="form-control phil-state" id="state">
        @foreach($states as $state)
            <option value="{{$state->provCode}}" @if($state->provCode === $user->refprovince) selected="selected" @endif>{{$state->provDesc}}</option>
        @endforeach
    </select>
</div>
<div class="form-group city">
    <label for="city">City</label><span class="required">*</span>
    <select name="city" class="form-control phil-city" id="city">
        @foreach($cities as $city)
            <option value="{{$city->citymunCode}}" @if($city->citymunCode === $user->refcitymun) selected="selected" @endif>{{$city->citymunDesc}}</option>
        @endforeach
    </select>
</div>
<div class="form-group postalcode">
    <label for="postalcode">Postal Code</label><span class="required">*</span>
    <input type="text" name="postalcode" class="form-control" id="postalcode" value="{{$user->postalcode}}">
</div>
<script>
    /*display the list of states when region dropdowm change event triggered*/
    let phil_region = $('.phil-region');
    let phil_state = $('.phil-state');
    let phil_city = $('.phil-city');
    let phil_regCode = function () {
        return $('.phil-region').val();
    };
    let phil_provCode = function () {
        return $('.phil-state').val();
    };


    $(document).ready(function () {

        phil_region.change(function () {
            /*clear state dropdown first*/
            phil_state.html("");
            phil_city.html("");
            $.ajax({
                'url' : '/address/state/'+phil_regCode(),
                'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                'type' : 'GET',
                'cache' : false,
                success: function(result){
                    phil_state.append('<option value="">Select State</option>');
                    $.each(result, function (key, value) {
                        phil_state.append('<option value="'+value.provCode+'">'+value.provDesc+'</option>');
                    });
                },error(xhr, status, error){
                    console.log("error: "+error+" status: "+status+" xhr: "+xhr);
                }
            })
        });

        phil_state.change(function () {
            /*clear city dropdown first*/
            phil_city.html("");
            $.ajax({
                'url' : '/address/city/'+phil_provCode(),
                'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                'type' : 'GET',
                'cache' : false,
                success: function(result){
                    phil_city.append('<option value="">Select City</option>');
                    $.each(result, function (key, value) {
                        phil_city.append('<option value="'+value.citymunCode+'">'+value.citymunDesc+'</option>');
                    });
                },error(xhr, status, error){
                    console.log("error: "+error+" status: "+status+" xhr: "+xhr);
                }
            })
        });
    });

</script>