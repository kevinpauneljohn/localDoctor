function clear_errors()
{
    let i;
    for (i = 0; i < arguments.length; i++) {

        if($('#'+arguments[i]).val().length > 0){
            $('.'+arguments[i]).closest('div.'+arguments[i]).removeClass('has-error').find('.text-danger').remove();
        }
    }
}


function modal_clear_errors()
{
    let i;
    for (i = 0; i < arguments.length; i++) {

        if($('#edit-client-form #'+arguments[i]).val().length > 0){
            $('.'+arguments[i]).closest('div.'+arguments[i]).removeClass('has-error').find('.text-danger').remove();
        }
    }
}

$(document).on('submit','#client-form',function(form){
    form.preventDefault();

    let value = $('#client-form').serialize();

    $.ajax({
        'url' : '/clients',
        'type' : 'POST',
        'data' : value,
        success: function(result)
        {
            console.log(result);
            if(result.success === true)
            {
                setTimeout(function(){
                    toastr.success('New Client Successfully Added!')

                    setTimeout(function(){
                        location.reload();
                    },1500);
                });
            }

            $.each(result, function (key, value) {
                var element = $('#'+key);

                element.closest('div.'+key)
                    .addClass(value.length > 0 ? 'has-error' : 'has-success')
                    .find('.text-danger')
                    .remove();
                element.after('<p class="text-danger">'+value+'</p>');
            });
        },error: function(xhr, status, error){
            console.log("error: "+error+" status: "+status+" xhr: "+xhr);
        }
    });
    clear_errors(
        'firstname',
        'lastname',
        'birthday',
        'landline',
        'mobileNo',
        'address',
        'region',
        'state',
        'city',
        'postalcode',
        'email',
        'username',
        'password',

    );
});


/***************Edit client section**************************/
$(document).on('click','.edit-client', function () {
    let id = this.id;
    $('.edit-user-id').val(id);
});
$(document).on('change','#select-edit-form',function () {
    let data = $('#select-edit-form').val();
    let id = $('.edit-user-id').val();

    $.ajax({
        'url' : '/client-form',
        'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        'type' : 'POST',
        'data' : {
            'option':data,
            'id': id,
        },
        success: function(result){
            $('#updateClientId').val(id);
            $('.form-content').html(result);
        },error: function(xhr, status, error){
        console.log("error: "+error+" status: "+status+" xhr: "+xhr);
        }
    });
});

$(document).on('submit','#edit-client-form', function (form) {
    form.preventDefault();

    let data = $('#edit-client-form').serialize();
    let id = $('#updateClientId').val();
    let selectForm = $('#select-edit-form').val();

    $.ajax({
        'url' : '/clients/'+id,
        'type' : 'POST',
        'data' : data,
        success: function(result){
            // console.log(result);

            if(result.success === true)
            {
                setTimeout(function(){
                    /*$('#role_form').trigger('reset');
                    $('#roles').modal('toggle');*/
                    toastr.success('User details Successfully updated!')

                    setTimeout(function(){
                        location.reload();
                    },1500);
                });
            }

            $.each(result, function (key, value) {
                // console.log(value);
                var element = $('#edit-client-form #'+key);

                element.closest('div.'+key)
                    .addClass(value.length > 0 ? 'has-error' : 'has-success')
                    .find('.text-danger')
                    .remove();
                element.after('<p class="text-danger">'+value+'</p>');
            });
        },error: function(xhr, status, error){
            console.log("error: "+error+" status: "+status+" xhr: "+xhr);
        }
    });

    if(selectForm === 'personal')
    {
        modal_clear_errors(
            'firstname',
            'lastname',
            'birthday',
            'landline',
            'mobileNo',
        );
    }else if (selectForm === 'billing'){
        modal_clear_errors(
            'address',
            'region',
            'state',
            'city',
        );
    }

});
/***************End Edit client section**********************/