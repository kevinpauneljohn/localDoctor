@extends('adminlte::page')

@section('title', 'User | Client')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">User | Client</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                <li class="breadcrumb-item active">Client</li>
            </ol>
        </div><!-- /.col -->
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            @can('add client')
                <button type="button" class="btn bg-gradient-primary btn-sm" data-toggle="modal" data-target="#add-new-client-modal"><i class="fa fa-plus-circle"></i> Add New</button>
            @endcan

        </div>
        <div class="card-body">
            <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4">
                <table id="roles-list" class="table table-bordered table-striped" role="grid">
                    <thead>
                    <tr role="row">
                        <th>Date Registered</th>
                        <th>First Name</th>
                        <th>Middle Name</th>
                        <th>Last Name</th>
                        <th>Username</th>
                        <th>Mobile No</th>
                        <th>Phone</th>
                        <th>Clinics</th>
                        <th>Action</th>
                    </tr>
                    </thead>

                    <tfoot>
                    <tr>
                        <th>Date Registered</th>
                        <th>First Name</th>
                        <th>Middle Name</th>
                        <th>Last Name</th>
                        <th>Username</th>
                        <th>Mobile No</th>
                        <th>Phone</th>
                        <th>Clinics</th>
                        <th>Action</th>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    @can('add client')
        <!--add new client modal-->
        <div class="modal fade" id="add-new-client-modal">
            <form role="form" id="client-form">
                @csrf
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Add New Client</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="container mt-3">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <h2 class="modal-form-title">Personal Information</h2>
                                        <div class="form-group firstname">
                                            <label for="firstname">First Name</label><span class="required">*</span>
                                            <input type="text" name="firstname" class="form-control" id="firstname">
                                        </div>
                                        <div class="form-group middlename">
                                            <label for="middlename">Middle Name</label>
                                            <input type="text" name="middlename" class="form-control" id="middlename">
                                        </div>
                                        <div class="form-group lastname">
                                            <label for="lastname">Last Name</label><span class="required">*</span>
                                            <input type="text" name="lastname" class="form-control" id="lastname">
                                        </div>
                                        <div class="form-group birthday">
                                            <label for="birthday">Date Of Birth</label>
                                                <input name="birthday" id="birthday" type="text" class="form-control" data-inputmask-alias="datetime" data-inputmask-inputformat="mm/dd/yyyy" data-mask>
                                        </div>
                                        <div class="form-group landline">
                                            <label>Phone</label>
                                                <input name="landline" id="landline" type="text" class="form-control" data-inputmask="'mask': '999 999 999'" data-mask>
                                        </div>
                                        <div class="form-group mobileNo">
                                            <label>Mobile</label>
                                                <input name="mobileNo" id="mobileNo" type="text" class="form-control" data-inputmask="'mask': '(9999) 999-9999'" data-mask>
                                        </div>
                                    </div>

                                    <div class="col-lg-4" style="border-left:solid 1px #e9ecef;border-right:solid 1px #e9ecef;">
                                        <h2 class="modal-form-title">Billing Address</h2>
                                        <div class="form-group address">
                                            <label for="address">Address</label><span class="required">*</span>
                                            <input type="text" name="address" class="form-control" id="address">
                                        </div>
                                        <div class="form-group region">
                                            <label for="region">Region</label><span class="required">*</span>
                                            <select name="region" id="region" class="form-control" style="width: 100%;">
                                                <option value="">Select a Region</option>
                                                @foreach($regions as $region)
                                                    <option value="{{$region->regCode}}">{{$region->regDesc}}</option>
                                                    @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group state">
                                            <label for="state">State</label><span class="required">*</span>
                                            <select name="state" class="form-control" id="state">

                                            </select>
                                        </div>
                                        <div class="form-group city">
                                            <label for="city">City</label><span class="required">*</span>
                                            <select name="city" class="form-control" id="city">

                                            </select>
                                        </div>
                                        <div class="form-group postalcode">
                                            <label for="postalcode">Postal Code</label><span class="required">*</span>
                                            <input type="text" name="postalcode" class="form-control" id="postalcode">
                                        </div>
                                    </div>

                                    <div class="col-lg-4">
                                        <h2 class="modal-form-title">Access Details</h2>
                                        <div class="form-group email">
                                            <label for="email">Email</label><span class="required">*</span>
                                            <input type="email" name="email" class="form-control" id="email">
                                        </div>
                                        <div class="form-group username">
                                            <label for="username">Username</label><span class="required">*</span>
                                            <input type="text" name="username" class="form-control" id="username">
                                        </div>
                                        <div class="form-group password">
                                            <label for="password">Password</label><span class="required">*</span>
                                            <input type="password" name="password" class="form-control" id="password">
                                        </div>
                                        <div class="form-group">
                                            <label for="password_confirmation">Re-type Password</label><span class="required">*</span>
                                            <input type="password" name="password_confirmation" class="form-control" id="password_confirmation">
                                        </div>
                                    </div>
                                </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </form>
        </div>
        <!--end add new client modal-->
    @endcan

    @can('edit client')
        <!--edit client modal-->
        <div class="modal fade" id="edit-new-client-modal">
            <form role="form" id="edit-client-form">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" id="updateClientId">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Update Client Details</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="container mt-3">
                               <div class="form-group">
                                   <input type="hidden" class="edit-user-id">
                                   <select class="form-control" name="option" id="select-edit-form">
                                       <option value="">-- Select details to be updated --</option>
                                       <option value="personal">Personal Information</option>
                                       <option value="billing">Billing Address</option>
                                   </select>
                               </div>
                                <div class="form-content"></div>
                            </div>
                            <div class="modal-footer justify-content-between">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </form>
        </div>
        <!--end add new client modal-->
    @endcan

    @can('delete user')
        <!--add new user modal-->
        <div class="modal fade" id="delete-role-modal">
            <form role="form" id="delete-role-form">
                @csrf
                @method('DELETE')
                {{--<input type="hidden" name="url" id="url" value="roles">--}}
                <div class="modal-dialog">
                    <div class="modal-content bg-danger">
                        <div class="modal-body">
                            <p class="delete_role">Delete Role: <span class="role-name"></span></p>
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
        <!--end add new user modal-->
    @endcan

@stop

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{asset('/css/style.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/datatables/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/select2/css/select2.min.css')}}">
    <style type="text/css">
        .delete_role{
            font-size: 20px;
        }
    </style>
@stop

@section('js')
    @can('view user')
        <script src="{{asset('vendor/datatables/js/dataTables.bootstrap4.min.js')}}"></script>
        <script src="{{asset('vendor/inputmask/min/jquery.inputmask.bundle.min.js')}}"></script>
        <script src="{{asset('vendor/inputmask/inputmask/inputmask.date.extensions.js')}}"></script>
        <script src="{{asset('vendor/select2/js/select2.full.min.js')}}"></script>
        <Script src="{{asset('js/client.js')}}"></Script>
        <Script src="{{asset('js/address.js')}}"></Script>
        <script>
            $(function() {
                $('#roles-list').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '{!! route('clients.list') !!}',
                    columns: [
                        { data: 'created_at', name: 'created_at'},
                        { data: 'firstname', name: 'firstname'},
                        { data: 'middlename', name: 'middlename'},
                        { data: 'lastname', name: 'lastname'},
                        { data: 'username', name: 'username'},
                        { data: 'mobileNo', name: 'mobileNo'},
                        { data: 'landline', name: 'landline'},
                        { data: 'clinics', name: 'clinics'},
                        { data: 'action', name: 'action', orderable: false, searchable: false}
                    ],
                    responsive:true,
                    order:[0,'desc']
                });

                //Money Euro
                $('[data-mask]').inputmask();
                $('.select2').select2();
            });
        </script>
    @endcan
@stop