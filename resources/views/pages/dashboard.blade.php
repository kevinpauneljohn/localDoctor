@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
    <p>Welcome to this beautiful admin panel.</p>
    <i class="fa fa-home"></i>
    {{config('terminal.license')}}
@stop

@section('css')
@stop

@section('js')
@stop