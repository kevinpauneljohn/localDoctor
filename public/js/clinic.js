function clear_errors()
{
    let i;
    for (i = 0; i < arguments.length; i++) {

        if($('#'+arguments[i]).val().length > 0){
            $('.'+arguments[i]).closest('div.'+arguments[i]).removeClass('has-error').find('.text-danger').remove();
        }
    }
}

function submitform(url , type , data , message , reload = true, elementAttr, consoleLog = true)
{
    $.ajax({
        'url' : url,
        'type' : type,
        'data' : data,
        'cache' : false,
        success: function(result, status, xhr){
            if(consoleLog === true)
            {
                console.log(result);
            }
            if(result.success === true)
            {
                setTimeout(function(){
                    toastr.success(message)
                    setTimeout(function(){
                        if(reload === true)
                        {
                            location.reload();
                        }
                    },1500);
                });
            }
            $.each(result, function (key, value) {
                var element = $(elementAttr+'#'+key);

                element.closest(elementAttr+'div.'+key)
                    .addClass(value.length > 0 ? 'has-error' : 'has-success')
                    .find('.text-danger')
                    .remove();
                element.after('<p class="text-danger">'+value+'</p>');
            });

        },error: function(xhr, status, error){
            console.log(xhr);
        }
    });
}
$(document).ready(function(){
    let addForm = $('#clinic-form');
    let editForm = $('#edit-clinic-form');

    addForm.submit(function(form){
        form.preventDefault();
        submitform(
            '/clinics',
            'POST',
            addForm.serialize() ,
            'New Clinic Successfully Created!',
            true,
            '',
            false
        );
        clear_errors("name","landline","mobileNo","address","state","city");
    });

    /*update clinic details*/
    editForm.submit(function (form) {
        form.preventDefault();

        let id = $('#edit-clinic-form #id').val();
        submitform(
            '/clinics/'+id,
            'POST',
            editForm.serialize() ,
            'Clinic Successfully Updated!',
            true,
            '',
            false
        );
        clear_errors("edit_name","edit_landline","edit_mobileNo","edit_address","edit_state","edit_city");
    });
});




$(document).on('click','.edit-btn',function(){
    let id = this.id;
    let selected = "";

    $.ajax({
        'url' : '/clinics/'+id,
        'type' : 'GET',
        'cache' : false,
        success: function(result){

            $('#id').val(result.id);
            $('#edit_name').val(result.name);
            $('#edit_landline').val(result.landline);
            $('#edit_mobileNo').val(result.mobile);
            $('#edit_address').val(result.address);
            $('#edit_state').val(result.state);

            $('#edit_city').append('<option value="">-- Select City --</option>');
            $.each(result.cities, function (key, value) {
                if(value.citymunCode === result.city)
                {
                    selected = ' selected="selected"';
                }
                $('#edit_city').append('<option value="'+value.citymunCode+'"'+selected+'>'+value.citymunDesc+'</option>');
            });
        },error: function(xhr, status, error){
            console.log(xhr);
        }
    });
});

$(document).on('click','.delete-btn',function () {
    let id = this.id;

    $.ajax({
        'url' : '/clinics/'+id,
        'type' : 'GET',
        'cache' : false,
        success: function (result) {
            $('#deleteClinicId').val(id);
            $('.clinic-name').text(result.name);
        },error: function(xhr, status, error){
            console.log(xhr);
        }
    });
});

$(document).on('submit','#delete-clinic-form',function (form) {
    form.preventDefault();

    let data = $('#delete-clinic-form');
    let id = $('#deleteClinicId').val();
    $.ajax({
        'url' : '/clinics/'+id,
        'type' : 'POST',
        'data' : data.serialize(),
        'cache' : false,
        success: function (result) {
            if(result.success === true)
            {
                setTimeout(function(){
                    toastr.success("Clinic Successfully Deleted")
                    setTimeout(function(){
                            location.reload();
                    },1500);
                });
            }
        },error: function(xhr, status, error){
            console.log(xhr);
        }
    });
});