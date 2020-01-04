<?php

namespace App\Http\Controllers;

use App\Clinic;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ClinicController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.Clinic.index');
    }

    public function clinicList()
    {
        $clinics = Clinic::all();

        return DataTables::of($clinics)
            ->addColumn('action', function ($clinic) {
                $action = '<a href="#" class="btn btn-xs btn-success"><i class="fa fa-eye"></i> View</a>';
                $action .= '<a href="#" class="btn btn-xs btn-primary"><i class="fa fa-edit"></i> Edit</a>';
                if(auth()->user()->hasAnyRole(['super admin']))
                {
                    $action .= '<a href="#" class="btn btn-xs btn-danger delete-job" data-toggle="modal" data-target="#delete-job-order" id="job-order-'.$clinic->id.'"><i class="fa fa-trash"></i> Delete</a>';
                }
                return $action;
            })
            ->rawColumns(['action','status'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
