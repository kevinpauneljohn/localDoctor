<?php

namespace App\Http\Controllers;

use App\License;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $region = DB::table('refregion')->get();
        return view('pages.Users.Client.index')->with([
            'regions'   => $region
        ]);
    }

    public function clientList()
    {
        $clients = User::where([
            ['category','=','client'],
            ['owner','=',1],
        ])->get();

        return DataTables::of($clients)
            ->addColumn('clinics',function($client){
                return "";
            })
            ->addColumn('action', function ($client) {
                $action ="";
                if(auth()->user()->hasPermissionTo('view client'))
                {
                    $action .= '<a href="'.route('clients.show',['client' => $client->id]).'"><button class="btn btn-xs btn-success view-client" id="'.$client->id.'"><i class="fa fa-eye"></i> View</button> </a>&nbsp;';
                }

                if(auth()->user()->hasPermissionTo('edit client'))
                {
                    $action .= '<button class="btn btn-xs btn-primary edit-client" id="'.$client->id.'" data-toggle="modal" data-target="#edit-new-client-modal"><i class="fa fa-edit"></i> Edit</button> &nbsp;';
                }
                if(auth()->user()->hasPermissionTo('delete client'))
                {
                    $action .= '<button class="btn btn-xs btn-danger delete-client" id="'.$client->id.'"><i class="fa fa-trash"></i> Delete</a>';
                }

                return $action;
            })
            ->rawColumns(['action','clinics'])
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
        $validator = Validator::make($request->all(), [
            'firstname'     => 'required',
            'lastname'      => 'required',
            'birthday'      => 'required',
            'landline'      => 'required',
            'username'      => 'required|unique:users,username',
            'email'         => 'required|unique:users,email',
            'password'      => 'required|confirmed',
            'mobileNo'      => 'required',
            'address'      => 'required',
            'region'      => 'required',
            'state'      => 'required',
            'city'      => 'required',
        ]);

        if($validator->passes())
        {
            $user = new User();
            $user->firstname = $request->firstname;
            $user->middlename = $request->middlename;
            $user->lastname = $request->lastname;
            $user->username = $request->username;
            $user->email = $request->email;
            $user->password = bcrypt($request->password);
            $user->mobileNo = $request->mobileNo;
            $user->landline = $request->landline;
            $user->birthday = $request->birthday;
            $user->address = $request->address;
            $user->refregion = $request->region;
            $user->refprovince = $request->state;
            $user->refcitymun = $request->city;
            $user->postalcode = $request->postalcode;
            $user->status = 'offline';
            $user->category = 'client';
            $user->owner = 1;
            $user->assignRole(['owner','client admin']);


            if($user->save())
            {
                return response()->json(['success' => true]);
                /*this code is suppose to generate license key*/
//                $license = new License();
//                $license->license = $this->generate_license_key();
//                $license->user_id = $user->id;
//                $license->save();
            }
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
        $users = User::find($id);
        $region = DB::table('refregion')->get();
        $state = DB::table('refprovince')
            ->where('regCode','=', $users->refregion)->get();
        $city = DB::table('refcitymun')
            ->where('provCode','=', $users->refprovince)->get();

        $client = User::where('id',$id)->count();
        if($client > 0)
        {
            return view('pages.Users.Client.profile')->with([
                'user'  => User::find($id),
                'address' => new AddressController(),
                'regions' => $region,
                'states' => $state,
                'cities' => $city,
//                'license' => DB::table('licenses')->where('user_id',$id)->first()->license,
            ]);
        }
        return abort(404);
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
     * Dec. 23, 2019
     * @author john kevin paunel
     * Display the form depending on the selected value
     * route: client.form
     * @param Request $request
     * @return mixed
     * */
    public function editForm(Request $request)
    {
        $view = "";
        $users = User::find($request->id);
        $region = DB::table('refregion')->get();
        $state = DB::table('refprovince')
            ->where('regCode','=', $users->refregion)->get();
        $city = DB::table('refcitymun')
            ->where('provCode','=', $users->refprovince)->get();

        if($request->option == 'personal')
        {
            $view = 'pages.Users.Client.forms.editPersonalinformation';
        }
        else if($request->option == 'billing')
        {
            $view = 'pages.Users.Client.forms.editBilling';
        }
        return view($view)->with([
            'user'  => $users,
            'regions' => $region,
            'states' => $state,
            'cities' => $city,
        ]);
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
        $validation = "";
        if($request->option === "personal")
        {
            $validation = [
                'firstname'     => 'required',
                'lastname'      => 'required',
                'birthday'      => 'required',
                'landline'      => 'required',
                'mobileNo'      => 'required',
            ];
        }else if($request->option === "billing")
        {
            $validation = [
                'address'       => 'required',
                'region'        => 'required',
                'state'         => 'required',
                'city'          => 'required',
                'postalcode'    => 'required',
            ];
        }
        $validator = Validator::make($request->all(), $validation);

        if($validator->passes())
        {
            $user = User::find($id);
            if($request->option === "personal")
            {
                $user->firstname = $request->firstname;
                $user->middlename = $request->middlename;
                $user->lastname = $request->lastname;
                $user->birthday = $request->birthday;
                $user->mobileNo = $request->mobileNo;
                $user->landline = $request->landline;
            }else if($request->option === "billing")
            {
                $user->address = $request->address;
                $user->refregion = $request->region;
                $user->refprovince = $request->state;
                $user->refcitymun = $request->city;
                $user->postalcode = $request->postalcode;
            }

            if($user->save())
            {
                return response()->json(['success' => true]);
            }
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

    /*custom codes*/

//    /**
//     * Dec. 15, 2019
//     * @author john kevin paunel
//     * generate license key for the client
//     * */
//    function generate_license_key() {
//
//        $tokens = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
//        $segment_chars = 5;
//        $num_segments = 4;
//        $key_string = '';
//
//        for ($i = 0; $i < $num_segments; $i++) {
//
//            $segment = '';
//
//            for ($j = 0; $j < $segment_chars; $j++) {
//                $segment .= $tokens[rand(0, 35)];
//            }
//
//            $key_string .= $segment;
//
//            if ($i < ($num_segments - 1)) {
//                $key_string .= '-';
//            }
//
//        }
//
//        /*check if license already exists*/
//        $unique_license = License::where('license',$key_string)->count();
//        if($unique_license > 0)
//        {
//            /*generate license key if its already exists*/
//            return $this->generate_license_key();
//        }else{
//            return $key_string;
//        }
//
//    }
}
