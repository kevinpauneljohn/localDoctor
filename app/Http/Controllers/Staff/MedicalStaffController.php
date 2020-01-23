<?php

namespace App\Http\Controllers\Staff;

use App\Clinic;
use App\ClinicUser;
use App\Events\CreateMedicalStaffEvent;
use App\Http\Controllers\Controller;
use App\Role;
use App\Threshold;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use GuzzleHttp\Client;

class MedicalStaffController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $provinces = DB::table('refprovince')->orderBy('provDesc','asc')->get();

        return view('pages.Users.medical_staff')->with([
            'clinics'   => Clinic::all(),
            'positions'  => Role::where([
                ['name','!=','employee'],
                ['name','!=','medical staff'],
                ['name','!=','owner'],
            ])->get(),
            'provinces' => $provinces,
        ]);
    }

    /**
     * Dec. 28, 2019
     * @author john kevin paunel
     * display all the medical staffs
     * */
    public function medicalStaffList()
    {
        $medicalStaffs = User::role('medical staff')->get();

        return DataTables::of($medicalStaffs)
            ->addColumn('position', function ($medicalStaff) {
               $positions = "";
               foreach ($medicalStaff->getRoleNames() as $position)
               {
                   $positions .= $this->roleColor($position);
               }

               return $positions;
            })
            ->addColumn('action', function ($medicalStaff) {
                $action = "";
                if(auth()->user()->hasPermissionTo('view medical staff'))
                {
                    $action .= '<button class="btn btn-xs btn-success view-staff" id="'.$medicalStaff->id.'"><i class="fa fa-eye"></i> View</button>';
                }
                if(auth()->user()->hasPermissionTo('edit medical staff'))
                {
                    $action .= '<button class="btn btn-xs btn-primary edit-staff" id="'.$medicalStaff->id.'"><i class="fa fa-edit"></i> Edit</button>';
                }
                if(auth()->user()->hasPermissionTo('delete medical staff')) {
                    $action .= '<button class="btn btn-xs btn-danger delete-staff" id="' . $medicalStaff->id . '"><i class="fa fa-trash"></i> Delete</a>';
                }

                return $action;
            })
            ->rawColumns(['action','position'])
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
        $validator = Validator::make($request->all(),[
            'clinic'    => 'required',
            'position'  => 'required',
            'firstname' => 'required',
            'lastname'  => 'required',
            'mobileNo'  => 'required',
            'address'  => 'required',
            'province'  => 'required',
            'city'  => 'required',
        ]);

        if($validator->passes())
        {
            $medical_staff = new User();
            if($this->staff($medical_staff, $request)->role($medical_staff, $request))
            {
                $clinicMember = $this->clinic($medical_staff,$request);

                /*pass the data to server*/
                $eventValue = event(new CreateMedicalStaffEvent($medical_staff, $clinicMember));
                return response()->json(['success' => true,'body' => $eventValue]);
            }
        }

        return response()->json($validator->errors());
    }

    /**
     * Jan. 18, 2020
     * @author john kevin paunel
     * create new medical staff
     * @param object $medical_staff
     * @param Request $request
     * @return mixed
     * */
    public function staff($medical_staff, $request)
    {
        $medical_staff->firstname = $request->firstname;
        $medical_staff->middlename = $request->middlename;
        $medical_staff->lastname = $request->lastname;
        $medical_staff->mobileNo = $request->mobileNo;
        $medical_staff->address = $request->address;
        $medical_staff->refprovince = $request->province;
        $medical_staff->refcitymun = $request->city;
        $medical_staff->status = 'offline';
        $medical_staff->category = 'client';
        //default role for medical staffs
        $medical_staff->assignRole('medical staff');
        $medical_staff->save();
        return $this;
    }
    /**
     * Jan. 18, 2020
     * @author john kevin paunel
     * add role to medical staff
     * @param object $medical_staff
     * @param Request $request
     * @return mixed
     * */
    public function role($medical_staff, $request)
    {
        foreach ($request->position as $role)
        {
            $medical_staff->assignRole($role);
        }
        return $this;
    }

    /**
     * Jan. 18, 2020
     * @author john kevin paunel
     * join the medical staff to a specific clinic
     * save the users to clinic_users table
     * @param object $medical_staff
     * @param Request $request
     * @return mixed
     * */
    public function clinic($medical_staff,$request)
    {
        $clinicMember = new ClinicUser();
        $clinicMember->clinic_id = $request->clinic;
        $clinicMember->user_id = $medical_staff->id;
        $clinicMember->save();

        return $clinicMember;
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

    /**
     * Jan. 03, 2020
     * @author john kevin paunel
     * check internet connection status
     * @return mixed
     * */
    private function checkInternetConnection()
    {
        $result = 1;
        if(!$sock = @fsockopen('doctorapp.devouterbox.com', 80))
        {
           $result = 0;
        }

        return $result;
    }


    /**
     * Jan. 03, 2020
     * @author john kevin paunel
     * Set the color badge of positions
     * @param string $role
     * @return string
     * */
    private function roleColor($role)
    {
        switch ($role)
        {
            case 'super admin':
                return '<span class="role-color badge badge-primary">'.$role.'</span>';
                break;
            case 'admin':
                return '<span class="role-color badge badge-info">'.$role.'</span>';
                break;
            case 'owner':
                return '<span class="role-color badge badge-success">'.$role.'</span>';
                break;
            case 'medical staff':
                return '<span class="role-color badge badge-warning">'.$role.'</span>';
                break;
            case 'medical doctor':
                return '<span class="role-color badge bg-fuchsia">'.$role.'</span>';
                break;
            case 'co-owner':
                return '<span class="role-color badge bg-gradient-pink">'.$role.'</span>';
                break;
            case 'HR':
                return '<span class="role-color badge bg-gradient-lime">'.$role.'</span>';
                break;
            case 'employee':
                return '<span class="role-color badge bg-gradient-dark">'.$role.'</span>';
                break;
            default:
                return '';
                break;
        }
    }

    public function syncServer()
    {
        $thresholds = Threshold::where('table','medicalStaff');

        //there'a a data saved in the threshold
        if($thresholds->count() > 0)
        {
            if($sock = @fsockopen('doctorapp.devouterbox.com', 80))
            {
                foreach ($thresholds->get() as $threshold)
                {
                    $medicalStaff = json_decode($threshold->data);

                    $body = $this->apiAuthorization(
                        User::findOrFail($medicalStaff->user_id)->api_token,
                        $medicalStaff->id,
                        $medicalStaff->firstname,
                        $medicalStaff->middlename,
                        $medicalStaff->lastname,
                        $medicalStaff->mobileNo,
                        $medicalStaff->address,
                        $medicalStaff->refprovince,
                        $medicalStaff->refcitymun,
                        $medicalStaff->created_at,
                        $medicalStaff->updated_at,
                        $medicalStaff->roles,
                        $medicalStaff->clinic_id,
                        $threshold->causer_id,
                        $threshold->action
                    );

//                    if($body === 1)
//                    {
//                        $this->deleteThreshold($threshold);
//                    }
                }
            }else{
                //no internet connection or the server is not online
                return response()->json(['message' => 'no internet connection']);
            }
        }
    }

    public function apiAuthorization($api_token, $id, $firstname, $middlename, $lastname, $mobileNo, $address, $refprovince, $refcitymun,
                                     $created_at, $updated_at, $roles, $clinic_id, $user_id, $action)
    {
        $client = new Client([
            'headers' => [
                'content-type' => 'application/json',
                'Accept' => 'application/json',
                'Authorization' => 'Bearer '.$api_token,
            ],
        ]);

        $response = $client->request('POST','https://doctorapp.devouterbox.com/api/create-medical-staff',[
            'json' => [
                'id'         => $id,
                'firstname'       => $firstname,
                'middlename'    => $middlename,
                'lastname'      => $lastname,
                'mobileNo'       => $mobileNo,
                'address'   => $address,
                'refprovince'     => $refprovince,
                'refcitymun'    => $refcitymun,
                'status'     => "offline",
                'category'     => "client",
                'created_at' => date('Y-m-d h:i:s', strtotime($created_at)),
                'updated_at' => date('Y-m-d h:i:s', strtotime($updated_at)),
                'roles'     => $roles,
                'clinic_id'     => $clinic_id,
                'user_id'     => $user_id,
                'terminal_id' => config('terminal.license'),
                'action'    => $action,
                'causer_id' => auth()->user()->id
            ],
        ]);

        return json_decode($response->getBody());
    }
}
