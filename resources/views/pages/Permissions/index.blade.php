@extends('adminlte::page')

@section('title', 'Permissions')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">Permissions</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                <li class="breadcrumb-item active">Permissions</li>
            </ol>
        </div><!-- /.col -->
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            {{--<button type="button" class="btn btn-block bg-gradient-primary btn-flat">Primary</button>--}}
            @can('add permission')
            <button type="button" class="btn bg-gradient-primary btn-sm" data-toggle="modal" data-target="#add-new-permission-modal"><i class="fa fa-plus-circle"></i> Add New</button>
                @endcan
        </div>
        <div class="card-body">
            <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4">
                <table id="permissions-list" class="table table-bordered table-striped" role="grid">
                    <thead>
                    <tr role="row">
                        <th>Permission</th>
                        <th>Assigned Roles</th>
                        <th>Action</th>
                    </tr>
                    </thead>

                    <tfoot>
                    <tr>
                        <th width="30%">Permission</th>
                        <th>Assigned Roles</th>
                        <th width="20%">Action</th>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    @can('add permission')
    <!--add new permissions modal-->
    <div class="modal fade" id="add-new-permission-modal">
        <form role="form" id="permission-form">
            @csrf
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Add New Permission</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group permission">
                            <label for="permission">Permission</label><span class="required">*</span>
                            <input type="text" name="permission" class="form-control" id="permission">
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </form>
    </div>
    <!--end add new roles modal-->
    @endcan

    @can('edit permission')
    <!--edit roles modal-->
    <div class="modal fade" id="edit-permission-modal">
        <form role="form" id="edit-permission-form">
            @csrf
            @method('PUT')
            <input type="hidden" name="id" id="id">
            <input type="hidden" name="url" id="url" value="permissions">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Edit permission</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group editPermission">
                            <label for="editPermission">Permission</label><span class="required">*</span>
                            <input type="text" name="editPermission" class="form-control" id="editPermission">
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </form>
    </div>
    <!--end edit permission modal-->
    @endcan

    @can('delete permission')
    <!--delete permission modal-->
    <div class="modal fade" id="delete-permission-modal">
        <form role="form" id="delete-permission-form">
            @csrf
            @method('DELETE')
            <input type="hidden" name="url" id="url" value="roles">
            <div class="modal-dialog">
                <div class="modal-content bg-danger">
                    <div class="modal-body">
                        <p class="delete_permission">Delete Permission: <span class="permission-name"></span></p>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-outline-light" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-outline-light">Delete</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </form>
    </div>
    <!--end delete modal-->
    @endcan

    @can('assign role to permission')
    <!--set/remove role-->
    <div class="modal fade" id="assign-role-modal">
        <form role="form" id="assign-role-form">
            @csrf
            <input type="hidden" name="assignRoleUrl" value="{{route('permissions.index')}}">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <strong style="margin-bottom: 20px;">Set/Remove Role</strong>
                        <div class="form-group clearfix">
                            @foreach($roles as $role)
                                <div class="icheck-primary">
                                    <input name="roles[]" type="checkbox" id="checkboxPrimary-{{$role->id}}" value="{{$role->name}}">
                                    <label for="checkboxPrimary-{{$role->id}}">
                                        {{$role->name}}
                                    </label>
                                </div>
                                @endforeach
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </form>
    </div>
    <!--end set/remove role-->
    @endcan

@stop

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{asset('/css/style.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/datatables/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css')}}">
    <style type="text/css">
        .delete_role{
            font-size: 20px;
        }
    </style>
@stop

@section('js')
    <script src="{{asset('vendor/datatables/js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('vendor/inputmask/min/jquery.inputmask.bundle.min.js')}}"></script>
    <Script src="{{asset('js/permission.js')}}"></Script>
    <script>
        $(function() {

            $('#permissions-list').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route('permissions.list') !!}',
                columns: [
                    { data: 'name', name: 'name'},
                    { data: 'roles', name: 'roles'},
                    { data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                responsive:true,
                createdRow: function( row, data, dataIndex ) {
                    $( row ).find('td:eq(1)').attr('class', 'roles-cell');
                }

            });
        });
    </script>
@stop