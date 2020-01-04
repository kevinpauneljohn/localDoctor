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
                                <label for="name">Name</label>
                                <input type="text" name="name" class="form-control" id="name">
                            </div>
                            <div class="form-group landline">
                                <label for="landline">Landline</label>
                                <input type="text" name="landline" class="form-control" id="landline" data-inputmask='"mask": "(999) 999-9999"' data-mask>
                            </div>
                            <div class="form-group mobileNo">
                                <label for="mobileNo">Mobile No.</label>
                                <input type="text" name="mobileNo" class="form-control" id="mobileNo" data-inputmask='"mask": "(999) 999-9999"' data-mask>
                            </div>

                            <div class="form-group address">
                                <label for="address">Street/House/Bldg. Address</label>
                                <input type="text" name="address" class="form-control" id="address">
                            </div>
                            <div class="row">
                                <div class="col-lg-6 state">
                                    <label for="state">State</label>
                                    <select class="form-control" name="state" id="state">

                                    </select>
                                </div>
                                <div class="col-lg-6 city">
                                    <label for="city">City</label>
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
        <!--end add new clinic modal-->
    @endcan
@stop

@section('css')
    <link rel="stylesheet" href="{{asset('/css/style.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/datatables/css/dataTables.bootstrap4.min.css')}}">
@stop

@section('js')
    <script src="{{asset('vendor/datatables/js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('vendor/inputmask/min/jquery.inputmask.bundle.min.js')}}"></script>
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
    </script>
@stop