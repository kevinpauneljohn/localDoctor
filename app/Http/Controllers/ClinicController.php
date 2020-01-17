<?php

namespace App\Http\Controllers;

use App\Clinic;
use App\Events\ClinicCreatedEvent;
use App\Threshold;
use App\User;
use GuzzleHttp\Client;
use http\Env\Response;
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
                event(new ClinicCreatedEvent($clinic));
                $response = true;
            }

            return response()->json(['success' => $response]);
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
        $validator = Validator::make($request->all(), [
            'edit_name'        => 'required',
            'edit_address'     => 'required',
            'edit_state'       => 'required',
            'edit_city'        => 'required',
            'edit_landline'    => 'required',
            'edit_mobileNo'    => 'required',
        ]);

        if($validator->passes())
        {
            $clinic = Clinic::find($id);
            $clinic->name = $request->edit_name;
            $clinic->address = $request->edit_address;
            $clinic->state = $request->edit_state;
            $clinic->city = $request->edit_city;
            $clinic->landline = $request->edit_landline;
            $clinic->mobiile = $request->edit_mobileNo;
            $clinic->user_id = auth()->user()->id;
            $clinic->status = "active";

            if($clinic->save())
            {
                return response()->json(['success' => true]);
            }
            return response()->json(['success' => false]);
        }

        return response()->json($validator->errors());
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

    /**
     * Jan. 16, 2020
     * @author john kevin
     * sync the data from thresholds table to server
     * */
    public function syncToServer()
    {
        $thresholds = Threshold::where('table','clinics');

        //there'a a data saved in the threshold
        if($thresholds->count() > 0)
        {
            if($sock = @fsockopen('doctorapp.devouterbox.com', 80))
            {
                foreach ($thresholds->get() as $threshold)
                {
                    $clinic = json_decode($threshold->data);

                    $body = $this->apiAuthorization(
                        User::findOrFail($clinic->user_id)->api_token,
                        $clinic->id,
                        $clinic->name,
                        $clinic->address,
                        $clinic->state,
                        $clinic->city,
                        $clinic->landline,
                        $clinic->mobile,
                        $clinic->user_id,
                        $clinic->status,
                        $clinic->created_at,
                        $clinic->updated_at,
                        'created'
                    );

                    if($body === 1)
                    {
                        $this->deleteThreshold($threshold);
                    }
                }
            }else{
                //no internet connection or the server is not online
                return response()->json(['message' => 'no internet connection']);
            }
        }

    }

    /**
     * Jan. 16. 2020
     * @author john kevin paunel
     * send data to server with api authorization
     * @param string $api_token
     * @param string $id
     * @param string $name
     * @param string $address
     * @param string $state
     * @param string $city
     * @param string $landline
     * @param string $mobile
     * @param string $user_id
     * @param string $status
     * @param string $created_at
     * @param string $updated_at
     * @param string $action
     * @return mixed
     * */
    public function apiAuthorization($api_token , $id , $name, $address , $state, $city, $landline, $mobile, $user_id, $status, $created_at, $updated_at, $action)
    {
        $client = new Client([
            'headers' => [
                'content-type' => 'application/json',
                'Accept' => 'application/json',
                'Authorization' => 'Bearer '.$api_token,
            ],
        ]);

        $response = $client->request('POST','https://doctorapp.devouterbox.com/api/create-clinic',[
            'json' => [
                'id'            => $id,
                'name'          => $name,
                'address'       => $address,
                'state'         => $state,
                'city'          => $city,
                'landline'      => $landline,
                'mobile'        => $mobile,
                'user_id'       => $user_id,
                'status'        => $status,
                'created_at'    => date('Y-m-d h:i:s', strtotime($created_at)),
                'updated_at'    => date('Y-m-d h:i:s', strtotime($updated_at)),
                'terminal_id'   => config('terminal.license'),
                'action'        => $action,
            ],
        ]);

        return json_decode($response->getBody());
    }

    /**
     * Jan. 17, 2020
     * @author john kevin paunel
     * Delete threshold from local DB
     * @return int
     * */
    public function deleteThreshold($threshold)
    {
        $threshold = Threshold::find($threshold->id);
        $threshold->delete();
        return 1;
    }
}
