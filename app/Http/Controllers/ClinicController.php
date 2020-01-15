<?php

namespace App\Http\Controllers;

use App\Clinic;
use App\Events\ClinicCreatedEvent;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
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
        $provinces = DB::table('refprovince')->orderBy('provDesc','asc')->get();
        return view('pages.Clinic.index')->with([
            'provinces' => $provinces,
        ]);
    }

    public function clinicList()
    {
        $clinics = Clinic::all();

        return DataTables::of($clinics)
            ->addColumn('action', function ($clinic) {
                $action = "";
                if(auth()->user()->hasPermissionTo('view clinic'))
                {
                    $action .= '<a href="#" class="btn btn-xs btn-success"><i class="fa fa-eye"></i> View</a>';
                }
                if(auth()->user()->hasPermissionTo('edit clinic'))
                {
                    $action .= '<a href="#" class="btn btn-xs btn-primary edit-btn" data-toggle="modal" data-target="#edit-clinic-modal" id="'.$clinic->id.'"><i class="fa fa-edit"></i> Edit</a>';
                }
                if(auth()->user()->hasPermissionTo('delete clinic'))
                {
                    $action .= '<a href="#" class="btn btn-xs btn-danger delete-btn" data-toggle="modal" data-target="#delete-job-order" id="job-order-'.$clinic->id.'"><i class="fa fa-trash"></i> Delete</a>';
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
        $validator = Validator::make($request->all() , [
            'name'      => 'required|unique:clinics,name',
            'address'   => 'required',
            'state'     => 'required',
            'city'      => 'required',
            'landline'  => 'required',
            'mobileNo'    => 'required',
        ]);


        if($validator->passes())
        {
            $response = false;

            $clinic = new Clinic();
            $clinic->name = $request->name;
            $clinic->landline = $request->landline;
            $clinic->mobile = $request->mobileNo;
            $clinic->address = $request->address;
            $clinic->state = $request->state;
            $clinic->city = $request->city;
            $clinic->user_id = auth()->user()->id;
            $clinic->status = "active";

            if($clinic->save())
            {

//                $client = new Client([
//                    'headers' => [
//                        'content-type' => 'application/json',
//                        'Accept' => 'application/json',
//                        'Authorization' => 'Bearer '.auth()->user()->api_token,
//                    ],
//                ]);
//
//                $response = $client->request('POST','https://doctorapp.devouterbox.com/api/create-clinic',[
//                    'json' => [
//                        'id'         => $clinic->id,
//                        'name'       => $clinic->name,
//                        'address'    => $clinic->address,
//                        'state'      => $clinic->state,
//                        'city'       => $clinic->city,
//                        'landline'   => $clinic->landline,
//                        'mobile'     => $clinic->mobile,
//                        'user_id'    => $clinic->user_id,
//                        'status'     => $clinic->status,
//                        'created_at' => date('Y-m-d h:i:s', strtotime($clinic->created_at)),
//                        'updated_at' => date('Y-m-d h:i:s', strtotime($clinic->updated_at)),
//                    ],
//                ]);

                //return response()->json(['success' => true,'body' => json_decode($response->getBody())]);
                //return $response->getBody();
                $response = true;
            }

            return response()->json(['success' => $response, 'body' => event(new ClinicCreatedEvent($clinic))]);
        }
        return response()->json($validator->errors());

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $clinic = Clinic::findOrFail($id);
        $cities = DB::table('refcitymun')->where('provCode',$clinic->state)->get();
        $object = [
            'id'  => $clinic->id,
            'name'  => $clinic->name,
            'address'  => $clinic->address,
            'state'  => $clinic->state,
            'city'  => $clinic->city,
            'landline'  => $clinic->landline,
            'mobile'  => $clinic->mobile,
            'user_id'  => $clinic->user_id,
            'cities'  => $cities,
        ];

        return $object;
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
