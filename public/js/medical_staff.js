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
    let addForm = $('#medical-staff-form');

    addForm.submit(function(form){
        form.preventDefault();
        submitform(
            '/medical-staffs',
            'POST',
            addForm.serialize() ,
            'New Medical Staff Successfully Created!',
            true,
            '',
            false
        );
        clear_errors("clinic","position","firstname","lastname","mobileNo","address","province","city");
    });
});


$(document).on('click','.edit-medical-staff',function(){
    let id = this.id;
    let selected = "";

    //console.log(id);

    $.ajax({
        'url'   : '/medical-staffs/'+id,
        'type'  : 'GET',
        'cache' : false,
        success: function(result)
        {
            // $.each(result, function (key, value) {
            //     console.log(value.roles[]);
            //
            // });
            //console.log(result[0]['refcitymun']);

            $('#edit_firstname').val(result[0]['firstname']);
            $('#edit_middlename').val(result[0]['middlename']);
            $('#edit_lastname').val(result[0]['lastname']);
            $('#edit_mobileNo').val(result[0]['mobileNo']);
            $('#edit_address').val(result[0]['address']);
            $('#edit_province').val(result[0]['refprovince']);
            $('#edit_city').append('<option value="">-- Select City --</option>');
            $.each(result[3], function (key, value) {
                console.log(value.citymunCode);
                if(result[0]['refcitymun'] === value.citymunCode)
                {
                    selected = ' selected="selected"';
                    console.log(selected);
                }
                $('#edit_city').append('<option value="'+value.citymunCode+'"'+selected+'>'+value.citymunDesc+'</option>');
                //$('#edit_city').append('<option value="'+value.citymunCode+'">'+value.citymunDesc+'</option>');
            });
        },error: function(xhr, status, error){
            console.log(xhr);
        }
    });
});