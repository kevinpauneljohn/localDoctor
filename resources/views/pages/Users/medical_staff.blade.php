@extends('adminlte::page')

@section('title', 'Medical Staffs')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">Medical Staffs</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                <li class="breadcrumb-item active">Medical Staff</li>
            </ol>
        </div><!-- /.col -->
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            @can('add medical staff')
                <button type="button" class="btn bg-gradient-primary btn-sm" data-toggle="modal" data-target="#add-new-medical-staff-modal"><i class="fa fa-plus-circle"></i> Add</button>
            @endcan

        </div>
        <div class="card-body">
            <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4">
                <table id="medical-staff-list" class="table table-bordered table-striped" role="grid">
                    <thead>
                    <tr role="row">
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Position</th>
                        <th>Action</th>
                    </tr>
                    </thead>

                    <tfoot>
                    <tr>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Position</th>
                        <th>Action</th>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>


    @can('add medical staff')
        <!--add new roles modal-->
        <div class="modal fade" id="add-new-medical-staff-modal">
            <form role="form" id="medical-staff-form">
                @csrf
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Add New Medical Staff</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group clinic">
                                <label for="clinic">Clinics</label><span class="required">*</span>
                                <select class="form-control select2" name="clinic" id="clinic" style="width:100%;">
                                    <option value=""> -- Select Clinic --</option>
                                    @foreach($clinics as $clinic)
                                        <option value="{{$clinic->id}}">{{$clinic->name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group position">
                                <label for="position">Position</label><span class="required">*</span>
                                <select class="form-control select2" name="position[]" id="position" multiple="multiple" style="width:100%;">
                                    <option value=""> -- Select Position --</option>
                                    @foreach($positions as $position)
                                        <option value="{{$position->name}}">{{$position->name}}</option>
                                    @endforeach
                                </select>
                            </div>

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
                            <div class="form-group mobileNo">
                                <label for="mobileNo">Mobile No.</label><span class="required">*</span>
                                <input type="text" name="mobileNo" class="form-control" id="mobileNo">
                            </div>
                            <div class="form-group address">
                                <label for="address">Street/House/Bldg. Address</label><span class="required">*</span>
                                <input type="text" name="address" class="form-control" id="address">
                            </div>
                            <div class="row">
                                <div class="col-lg-6 province">
                                    <label for="province">Province</label><span class="required">*</span>
                                    <select class="form-control" name="province" id="province">
                                        <option value="">-- Select Province</option>
                                            @foreach($provinces as $province)
                                                <option value="{{$province->provCode}}">{{$province->provDesc}}</option>
                                            @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-6 city">
                                    <label for="city">City</label><span class="required">*</span>
                                    <select class="form-control" name="city" id="city">

                                    </select>
                                </div>
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

    @can('edit medical staff')
        <!--edit roles modal-->
        <div class="modal fade" id="edit-role-modal">
            <form role="form" id="edit-role-form" action="{{route('roles.update',['role' => 1])}}">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" id="id">
                <input type="hidden" name="url" id="url" value="roles">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Edit Role</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group editRole">
                                <label for="editRole">Role Name</label><span class="required">*</span>
                                <input type="text" name="editRole" class="form-control" id="editRole">
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
        <!--end edit roles modal-->
    @endcan

    @can('delete medical staff')
        <!--add new roles modal-->
        <div class="modal fade" id="delete-role-modal">
            <form role="form" id="delete-role-form">
                @csrf
                @method('DELETE')
                <input type="hidden" name="url" id="url" value="roles">
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
        <!--end add new roles modal-->
    @endcan

@stop

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{asset('/css/style.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/datatables/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/select2/css/select2.min.css')}}">
    <style type="text/css">
        .delete_role{
            font-size: 20px;
        }
    </style>
@stop

@section('js')
    @can('view medical staff')
        <script src="{{asset('vendor/datatables/js/dataTables.bootstrap4.min.js')}}"></script>
        <script src="{{asset('vendor/select2/js/select2.full.min.js')}}"></script>
        <script src="{{asset('js/medical_staff.js')}}"></script>
        <script>
            $(function() {
                $('#medical-staff-list').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '{!! route('medicalStaffs.list') !!}',
                    columns: [
                        { data: 'firstname', name: 'firstname'},
                        { data: 'lastname', name: 'lastname'},
                        { data: 'position', name: 'position'},
                        { data: 'action', name: 'action', orderable: false, searchable: false}
                    ],
                    responsive:true,
                    order:[0,'desc']
                });
            });

            /*select2 initialize*/
            $('.select2').select2();

            /*fetch city*/
            $(document).ready(function(){
                let medicalForm = $('#medical-staff-form');
                let province = $('#medical-staff-form #province');
                let city = $('#medical-staff-form #city');

                province.change(function () {
                    city.html("");
                    $.ajax({
                        'url' : '/address/city/'+province.val(),
                        'type' : 'GET',
                        success: function(result){
                            city.append('<option value="">-- Select City --</option>');
                            $.each(result, function ( key , value ) {
                                city.append('<option value="'+value.citymunCode+'">'+value.citymunDesc+'</option>');
                            });
                        },error(xhr, status, error){
                            console.log("error: "+error+" status: "+status+" xhr: "+xhr);
                        }
                    });
                });


                medicalForm.submit(function(form){
                    form.preventDefault();
                    let value = medicalForm.serialize();

                });
            });
        </script>
    @endcan
@stop