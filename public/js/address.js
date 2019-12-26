/*display the list of states when region dropdowm change event triggered*/
let region = $('#region');
let state = $('#state');
let city = $('#city');
let regCode = function () {
    return $('#region').val();
};
let provCode = function () {
    return $('#state').val();
};


$(document).ready(function () {

    region.change(function () {
        /*clear state dropdown first*/
        state.html("");
        city.html("");
        $.ajax({
            'url' : '/address/state/'+regCode(),
            'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            'type' : 'GET',
            'cache' : false,
            success: function(result){
                state.append('<option value="">Select State</option>');
                $.each(result, function (key, value) {
                    state.append('<option value="'+value.provCode+'">'+value.provDesc+'</option>');
                });
            },error(xhr, status, error){
                console.log("error: "+error+" status: "+status+" xhr: "+xhr);
            }
        })
    });

    state.change(function () {
        /*clear city dropdown first*/
        city.html("");
        $.ajax({
            'url' : '/address/city/'+provCode(),
            'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            'type' : 'GET',
            'cache' : false,
            success: function(result){
                city.append('<option value="">Select City</option>');
                $.each(result, function (key, value) {
                    city.append('<option value="'+value.citymunCode+'">'+value.citymunDesc+'</option>');
                });
            },error(xhr, status, error){
                console.log("error: "+error+" status: "+status+" xhr: "+xhr);
            }
        })
    });
});
