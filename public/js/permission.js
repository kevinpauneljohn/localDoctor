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
$(document).on('submit','#permission-form', function(form){
    form.preventDefault();

    let value = $('#permission-form').serialize();

    $.ajax({
        'url' : '/permissions',
        'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        'type' : 'POST',
        'data' : value,
        'cache' : false,
        success: function(result, status, xhr){
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
                var element = $('#'+key);

                element.closest('div.'+key)
                    .addClass(value.length > 0 ? 'has-error' : 'has-success')
                    .find('.text-danger')
                    .remove();
                element.after('<p class="text-danger">'+value+'</p>');
            });

        },error: function(xhr, status, error){
            console.log(xhr);
        }
    });
    clear_errors('permission');
});

/*end of add new role*/

/*flash edit form*/
var edit_modal = $('#edit-permission-modal');
$(document).on('click','.edit-permission',function () {
    edit_modal.modal('show');

    $tr = $(this).closest('tr');

    var data = $tr.children("td").map(function () {
        return $(this).text();
    }).get();

    $('#id').val(this.id);
    $('#editPermission').val(data[0]);
});
/*End of flash edit form*/

/*store update permission name*/
$(document).on('submit','#edit-permission-form',function (form) {
    form.preventDefault();
    let url = $('#url').val();
    let id = $('#id').val();
    let value = $('#edit-permission-form').serialize();

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
                    toastr.success('Permission Successfully Updated!')

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
    clear_errors('editPermission');
});
/*end store update permission name*/

$(document).ready(function(){
    $("#assign-role-modal").on('hidden.bs.modal', function(){
        //$('input[type=checkbox]').removeAttr("checked");
        $('input[type=checkbox]').prop('checked',false);
        $('.role-form-id').remove();
    });
});

/*flash assign role form*/
var assign_role_modal = $('#assign-role-modal');
$(document).on('click','.assign-role',function () {
    assign_role_modal.modal('show');

    $tr = $(this).closest('tr');

    var data = $tr.children("td").map(function () {
        return $(this).text();
    }).get();

    $('#assign-role-modal .modal-title').text(data[0]);
    let id = this.id;
    $('#assign-role-form').append('<input class="role-form-id" type="hidden" name="id" value="'+id+'">');
    $.ajax({
        'url' : '/permissions/'+id,
        'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        'type' : 'GET',
        'cache' : false,
        success: function (result, status, xhr)
        {
            $.each(result, function(key, value){
                $('#checkboxPrimary-'+value.id).prop(   'checked',true);
            });
        },error: function (xhr, status, error) {
            console.log("error: "+error+" status: "+status+" xhr: "+xhr);
        }
    });
});
/*End of flash assign role form*/

$(document).on('submit','#assign-role-form', function(form){
    form.preventDefault();
    value = $('#assign-role-form').serialize();

    //console.log(value);
    $.ajax({
        'url' : '/permission-set-roles',
        'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        'type' : 'POST',
        'data' : value,
        'cache' : false,
        success: function (result, status, xhr)
        {
            if(result.success === true)
            {
                $('#permissions-list tr #data-cell-'+result.id+' .badge').remove();
                $.each(result.roles, function (key, value) {
                    $('#permissions-list tr #data-cell-'+result.id).append('<span class="badge badge-primary">'+value+'</span>');
                });

            }
        },error: function (xhr, status, error) {
            console.log("error: "+error+" status: "+status+" xhr: "+xhr);
        }
    });
});