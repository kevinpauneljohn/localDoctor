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

    //console.log(id);

    $.ajax({
        'url'   : '/medical-staffs/'+id,
        'type'  : 'GET',
        'cache' : false,
        success: function(result)
        {
            console.log(result);
        },error: function(xhr, status, error){
            console.log(xhr);
        }
    });
});