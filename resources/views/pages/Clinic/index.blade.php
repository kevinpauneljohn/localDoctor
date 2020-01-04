@extends('adminlte::page')

@section('title', 'Clinics')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">Clinic</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                <li class="breadcrumb-item active">Clinic</li>
            </ol>
        </div><!-- /.col -->
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            @can('add clinic')
                <button type="button" class="btn bg-gradient-primary btn-sm" data-toggle="modal" data-target="#add-new-clinic-modal"><i class="fa fa-plus-circle"></i> Add New</button>
            @endcan

        </div>
        <div class="card-body">
            <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4">
                <table id="clinic-list" class="table table-bordered table-striped" role="grid">
                    <thead>
                    <tr role="row">
                        <th>Name</th>
                        <th>Address</th>
                        <th>Landline</th>
                        <th>Mobile No</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    </thead>

                    <tfoot>
                    <tr>
                        <th>Name</th>
                        <th>Address</th>
                        <th>Landline</th>
                        <th>Mobile No</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    @can('add clinic')
        <!--add new clinic modal-->
        <div class="modal fade" id="add-new-clinic-modal">
            <form role="form" id="clinic-form">
                @csrf
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Add New Clinic</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group name">
                                <label for="name">Name</label><span class="required">*</span>
                                <input type="text" name="name" class="form-control" id="name">
                            </div>
                            <div class="form-group landline">
                                <label for="landline">Landline</label><span class="required">*</span>
                                <input type="text" name="landline" class="form-control" id="landline" data-inputmask='"mask": "(999) 999-9999"' data-mask>
                            </div>
                            <div class="form-group mobileNo">
                                <label for="mobileNo">Mobile No.</label><span class="required">*</span>
                                <input type="text" name="mobileNo" class="form-control" id="mobileNo" data-inputmask='"mask": "(9999) 999-9999"' data-mask>
                            </div>

                            <div class="form-group address">
                                <label for="address">Street/House/Bldg. Address</label><span class="required">*</span>
                                <input type="text" name="address" class="form-control" id="address">
                            </div>
                            <div class="row">
                                <div class="col-lg-6 state">
                                    <label for="state">State</label><span class="required">*</span>
                                    <select class="form-control state-list" name="state" id="state">
                                        <option value=""> -- Select State -- </option>
                                        @foreach($provinces as $province)
                                            <option value="{{$province->provCode}}">{{$province->provDesc}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-6 city">
                                    <label for="city">City</label><span class="required">*</span>
                                    <select class="form-control city-list" name="city" id="city">

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
        <!--end add new clinic modal-->
    @endcan

    @can('edit clinic')
        <!--edit clinic modal-->
        <div class="modal fade" id="edit-clinic-modal">
            <form role="form" id="edit-clinic-form">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" id="id">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Edit Clinic</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group edit_name">
                                <label for="edit_name">Name</label><span class="required">*</span>
                                <input type="text" name="edit_name" class="form-control" id="edit_name">
                            </div>
                            <div class="form-group edit_landline">
                                <label for="edit_landline">Landline</label><span class="required">*</span>
                                <input type="text" name="edit_landline" class="form-control" id="edit_landline" data-inputmask='"mask": "(999) 999-9999"' data-mask>
                            </div>
                            <div class="form-group edit_mobileNo">
                                <label for="edit_mobileNo">Mobile No.</label><span class="required">*</span>
                                <input type="text" name="edit_mobileNo" class="form-control" id="edit_mobileNo" data-inputmask='"mask": "(9999) 999-9999"' data-mask>
                            </div>

                            <div class="form-group edit_address">
                                <label for="edit_address">Street/House/Bldg. Address</label><span class="required">*</span>
                                <input type="text" name="edit_address" class="form-control" id="edit_address">
                            </div>
                            <div class="row">
                                <div class="col-lg-6 edit_state">
                                    <label for="edit_state">State</label><span class="required">*</span>
                                    <select class="form-control state-list" name="edit_state" id="edit_state">
                                        <option value=""> -- Select State -- </option>
                                        @foreach($provinces as $province)
                                            <option value="{{$province->provCode}}">{{$province->provDesc}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-6 edit_city">
                                    <label for="edit_city">City</label><span class="required">*</span>
                                    <select class="form-control city-list" name="edit_city" id="edit_city">

                                    </select>
                                </div>
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
        <!--end edit clinic modal-->
    @endcan
@stop

@section('css')
    <link rel="stylesheet" href="{{asset('/css/style.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/datatables/css/dataTables.bootstrap4.min.css')}}">
@stop

@section('js')
    <script src="{{asset('vendor/datatables/js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('vendor/inputmask/min/jquery.inputmask.bundle.min.js')}}"></script>
    <script src="{{asset('js/clinic.js')}}"></script>
    <script>
        $(function() {
            $('#clinic-list').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route('clinics.list') !!}',
                columns: [
                    { data: 'name', name: 'name'},
                    { data: 'address', name: 'address'},
                    { data: 'landline', name: 'landline'},
                    { data: 'mobile', name: 'mobile' },
                    { data: 'status', name: 'status'},
                    { data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                responsive:true,
                order:[0,'desc']
            });

            $('[data-mask]').inputmask();
        });

        /*fetch city*/

        function cityAddress(city,state)
        {
            city.html("");
            $.ajax({
                'url' : '/address/city/'+state.val(),
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
        }
        $(document).ready(function(){
            let state = $('#clinic-form .state-list');
            let city = $('#clinic-form .city-list');

            state.change(function () {
                cityAddress(city,state);
            });

            $('#edit-clinic-form .state-list').change(function(){
                cityAddress($('#edit-clinic-form .city-list') , $('#edit-clinic-form .state-list'));
            });
        });
    </script>
@stop