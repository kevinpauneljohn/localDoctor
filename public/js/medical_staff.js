function clear_errors()
{
    let i;
    for (i = 0; i < arguments.length; i++) {

        if($('#'+arguments[i]).val().length > 0){
            $('.'+arguments[i]).closest('div.'+arguments[i]).removeClass('has-error').find('.text-danger').remove();
        }
    }
}

$(document).ready(function(){
    let form = $('#medical-staff-form');

    form.submit(function(addForm){
        addForm.preventDefault();
        $.ajax({
            'url' : '/medical-staffs',
            'type' : 'POST',
            'data' : form.serialize(),
            'cache' : false,
            success: function(result, status, xhr){
                console.log(result);
                if(result.success === true)
                {
                    setTimeout(function(){
                        toastr.success('New Permission Successfully Added!')

                        setTimeout(function(){
                            location.reload();
                        },1500);
                    });
                }
                $.each(result, function (key, value) {
                    var element = $('#medical-staff-form #'+key);

                    element.closest('#medical-staff-form div.'+key)
                        .addClass(value.length > 0 ? 'has-error' : 'has-success')
                        .find('.text-danger')
                        .remove();
                    element.after('<p class="text-danger">'+value+'</p>');
                });

            },error: function(xhr, status, error){
                console.log(xhr);
            }
        });
        clear_errors("clinic","position","firstname","lastname","mobileNo","address","province","city");
    });
});