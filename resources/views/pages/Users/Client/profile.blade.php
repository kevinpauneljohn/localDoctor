@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">Client | Profile</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('clients.index')}}">Client</a></li>
                <li class="breadcrumb-item active">Clients Profile</li>
            </ol>
        </div><!-- /.col -->
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-md-4">

            <!-- Profile Image -->
            <div class="card card-primary card-outline user-profile">
                <span class="action-btn">
                    @can('delete client')
                        <button type="button" class="btn btn-xs fa-pull-right delete" title="delete" data-toggle="modal" data-target="#delete-client"><i class="fa fa-trash"></i></button>
                    @endcan
                    @can('edit client')
                        <button type="button" class="btn btn-xs no-border fa-pull-right edit" title="edit"><i class="fa fa-pen"  data-toggle="modal" data-target="#edit-client"></i></button>
                    @endcan
                </span>

                <div class="card-body box-profile">
                    <div class="text-center">
                        <img class="profile-user-img img-fluid img-circle" src="{{asset('images/profile/male_profile.png')}}" alt="User profile picture">
                    </div>

                    <h3 class="profile-username text-center">
                        {{ucfirst($user->firstname)}} {{isset($user->middlename) ? ucfirst($user->middlename)[0].'.':''}} {{ucfirst($user->lastname)}}

                    </h3>

                    <p class="text-muted text-center">
                        @foreach($user->getRoleNames() as $roles)
                                <label class="badge bg-cyan">{{$roles}}</label>
                            @endforeach
                    </p>

                    <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item">
                            <b>Username</b> <a class="float-right">{{$user->username}}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Phone</b> <a class="float-right">{{$user->landline}}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Mobile Phone</b> <a class="float-right">{{$user->mobileNo}}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Email</b> <a class="float-right">{{$user->email}}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Date Of Birth</b> <a class="float-right">{{$user->birthday}}</a>
                        </li>
                    </ul>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->

            <!-- About Me Box -->
            <div class="card card-primary address-container">
                <div class="card-header">
                    <h3 class="card-title">Billing Address</h3>
                </div>
                <span class="address-action-btn">
                    @can('delete client')
                        <button type="button" class="btn btn-xs fa-pull-right delete" title="delete" data-toggle="modal" data-target="#delete-client"><i class="fa fa-trash"></i></button>
                    @endcan
                    @can('edit client')
                        <button type="button" class="btn btn-xs no-border fa-pull-right edit" title="edit"><i class="fa fa-pen"  data-toggle="modal" data-target="#edit-client-address"></i></button>
                    @endcan
                </span>
                <!-- /.card-header -->
                <div class="card-body">

                    <p class="text-muted">{{$user->address}}, {{$address->getCityName($user->refcitymun)}}, {{$address->getStateName($user->refprovince)}}, {{$user->postalcode}}</p>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
        <!-- /.col -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header p-2">
                    <ul class="nav nav-pills">
                        <li class="nav-item"><a class="nav-link active" href="#clinics" data-toggle="tab">Clinics</a></li>
                        <li class="nav-item"><a class="nav-link" href="#employee" data-toggle="tab">Employee</a></li>
                        <li class="nav-item"><a class="nav-link" href="#settings" data-toggle="tab">Settings</a></li>
                    </ul>
                </div><!-- /.card-header -->
                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane active" id="clinics">
                        </div>
                        <!-- /.tab-pane -->
                        <div class="tab-pane" id="employee">
                        </div>
                        <!-- /.tab-pane -->

                        <div class="tab-pane" id="settings">
                        </div>
                        <!-- /.tab-pane -->
                    </div>
                    <!-- /.tab-content -->
                </div><!-- /.card-body -->
            </div>
            <!-- /.nav-tabs-custom -->
        </div>
        <!-- /.col -->
    </div>

    @can('edit client')
        <!--edit client modal-->
        <div class="modal fade" id="edit-client">
            <form role="form" id="edit-client-form">
                @csrf
                @method('PUT')
                <input type="hidden" name="option" value="personal" id="select-edit-form">
                <input type="hidden" name="id" id="updateClientId" value="{{$user->id}}">
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

                                <input type="hidden" name="url" id="url" value="clients">
                                <div class="form-group firstname">
                                    <label for="firstname">First Name</label><span class="required">*</span>
                                    <input type="text" name="firstname" class="form-control" id="firstname" value="{{$user->firstname}}">
                                </div>
                                <div class="form-group middlename">
                                    <label for="middlename">Middle Name</label>
                                    <input type="text" name="middlename" class="form-control" id="middlename" value="{{$user->middlename}}">
                                </div>
                                <div class="form-group lastname">
                                    <label for="lastname">Last Name</label><span class="required">*</span>
                                    <input type="text" name="lastname" class="form-control" id="lastname" value="{{$user->lastname}}">
                                </div>
                                <div class="form-group birthday">
                                    <label for="birthday">Date Of Birth</label>
                                    <input name="birthday" id="birthday" type="text" class="form-control" data-inputmask-alias="datetime" data-inputmask-inputformat="mm/dd/yyyy" data-mask  value="{{$user->birthday}}">
                                </div>
                                <div class="form-group landline">
                                    <label>Phone</label>
                                    <input name="landline" id="landline" type="text" class="form-control" data-inputmask="'mask': '999 999 999'" data-mask value="{{$user->landline}}">
                                </div>
                                <div class="form-group mobileNo">
                                    <label>Mobile</label>
                                    <input name="mobileNo" id="mobileNo" type="text" class="form-control" data-inputmask="'mask': '(9999) 999-9999'" data-mask value="{{$user->mobileNo}}">
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

        {{--edit address--}}
        <div class="modal fade" id="edit-client-address">
            <form role="form" id="edit-client-address-form">
                @csrf
                @method('PUT')
                <input type="hidden" name="option" value="billing" id="select-edit-form">
                <input type="hidden" name="id" id="updateClientId" value="{{$user->id}}">
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
                                <div class="form-group address">
                                    <label for="address">Address</label><span class="required">*</span>
                                    <input type="text" name="address" class="form-control" id="address" value="{{$user->address}}">
                                </div>
                                <div class="form-group region">
                                    <label for="region">Region</label><span class="required">*</span>
                                    <select name="region" id="region" class="form-control phil-region" style="width: 100%;">
                                        @foreach($regions as $region)
                                            <option value="{{$region->regCode}}" @if($region->regCode === $user->refregion) selected="selected" @endif>{{$region->regDesc}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group state">
                                    <label for="state">State</label><span class="required">*</span>
                                    <select name="state" class="form-control phil-state" id="state">
                                        @foreach($states as $state)
                                            <option value="{{$state->provCode}}" @if($state->provCode === $user->refprovince) selected="selected" @endif>{{$state->provDesc}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group city">
                                    <label for="city">City</label><span class="required">*</span>
                                    <select name="city" class="form-control phil-city" id="city">
                                        @foreach($cities as $city)
                                            <option value="{{$city->citymunCode}}" @if($city->citymunCode === $user->refcitymun) selected="selected" @endif>{{$city->citymunDesc}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group postalcode">
                                    <label for="postalcode">Postal Code</label><span class="required">*</span>
                                    <input type="text" name="postalcode" class="form-control" id="postalcode" value="{{$user->postalcode}}">
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
        {{--end edit address--}}
    @endcan
@stop

@section('css')
{{--    <meta name="csrf-token" content="{{ csrf_token() }}">--}}
    <link rel="stylesheet" href="{{asset('/css/style.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/datatables/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/select2/css/select2.min.css')}}">
@stop

@section('js')
    <script src="{{asset('vendor/datatables/js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('vendor/inputmask/min/jquery.inputmask.bundle.min.js')}}"></script>
    <script src="{{asset('vendor/inputmask/inputmask/inputmask.date.extensions.js')}}"></script>
    <script src="{{asset('vendor/select2/js/select2.full.min.js')}}"></script>
    <Script src="{{asset('js/client.js')}}"></Script>
    <Script src="{{asset('js/address.js')}}"></Script>
       <script>
           $('[data-mask]').inputmask();
       </script>

    @can('edit client')
        <script>
            function modal_clear_errors()
            {
                let i;
                for (i = 0; i < arguments.length; i++) {

                    if($('#edit-client-address-form #'+arguments[i]).val().length > 0){
                        $('.'+arguments[i]).closest('div.'+arguments[i]).removeClass('has-error').find('.text-danger').remove();
                    }
                }
            }

            $(document).on('submit','#edit-client-address-form', function (form) {
                form.preventDefault();

                let data = $('#edit-client-address-form').serialize();
                let id = $('#updateClientId').val();

                $.ajax({
                    'url' : '/clients/'+id,
                    'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
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
                            var element = $('#edit-client-address-form #'+key);

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


                    modal_clear_errors(
                        'address',
                        'region',
                        'state',
                        'city',
                    );

            });
        </script>
    @endcan
@stop