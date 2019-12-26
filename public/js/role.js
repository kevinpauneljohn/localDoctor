function clear_errors()
{
    let i;
    for (i = 0; i < arguments.length; i++) {

        if($('#'+arguments[i]).val().length > 0){
            $('.'+arguments[i]).closest('div.'+arguments[i]).removeClass('has-error').find('.text-danger').remove();
        }
    }
}
/*add new role*/
$(document).on('submit','#role-form', function(form){
    form.preventDefault();

    let value = $('#role-form').serialize();

    $.ajax({
        'url' : '/roles',
        'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        'type' : 'POST',
        'data' : value,
        'cache' : false,
        success: function(result, status, xhr){
            if(result.success === true)
            {
                setTimeout(function(){
                    /*$('#role_form').trigger('reset');
                    $('#roles').modal('toggle');*/
                    toastr.success('New Role Successfully Added!')

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

        }
    });
    clear_errors('role');
});

/*end of add new role*/

/*flash edit form*/
var edit_modal = $('#edit-role-modal');
$(document).on('click','.edit-role',function () {
    edit_modal.modal('show');

    $tr = $(this).closest('tr');

    var data = $tr.children("td").map(function () {
        return $(this).text();
    }).get();

    $('#id').val(this.id);
    $('#editRole').val(data[0]);
});
/*End of flash edit form*/

/*store update role name*/
$(document).on('submit','#edit-role-form',function (form) {
    form.preventDefault();
    let url = $('#url').val();
    let id = $('#id').val();
    let value = $('#edit-role-form').serialize();

    $.ajax({
        'url' : '/'+url+'/'+id,
        'type' : 'POST',
        'data' : value,
        'cache' : false,
        success: function (result, status, xhr) {
            if(result.success === true)
            {
                setTimeout(function(){
                    /*$('#role_form').trigger('reset');
                    $('#roles').modal('toggle');*/
                    toastr.success('Role Successfully Updated!')

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
    clear_errors('editRole');
});
/*end store update role name*/

/* flash delete role form*/
$(document).on('click','.delete-role',function () {
    $('#delete-role-modal').modal('show');

    $tr = $(this).closest('tr');

    var data = $tr.children("td").map(function () {
        return $(this).text();
    }).get();

    $('.role-name').html(
        '<strong>'+data[0]+'</strong>' +
        '<input type="hidden" name="deleteRoleId" id="deleteRoleId" value="'+this.id+'">'
    );
});
/* end flash delete role form*/
/*store update role name*/
$(document).on('submit','#delete-role-form',function (form) {
    form.preventDefault();
    let url = $('#url').val();
    let id = $('#deleteRoleId').val();
    let value = $('#delete-role-form').serialize();

    $.ajax({
        'url' : '/'+url+'/'+id,
        'type' : 'POST',
        'data' : value,
        'cache' : false,
        success: function (result, status, xhr) {
            console.log(result);
            if(result.success === true)
            {
                setTimeout(function(){
                    toastr.success('Role Successfully Deleted!')

                    setTimeout(function(){
                        location.reload();
                    },1500);
                });
            }
        },error: function(xhr, status, error){
            console.log("error: "+error+" status: "+status+" xhr: "+xhr);
        }
    });
});
/*end store update role name*/