<?php

namespace App\Http\Controllers\Staff;

use App\Clinic;
use App\Http\Controllers\Controller;
use App\Http\Controllers\RolesColorController;
use App\Role;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

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
            'color' => new RolesColorController(),
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
                if(auth()->user()->hasPermissionTo('edit medical staff'))
                {
                    $action .= '<button class="btn btn-xs btn-primary edit-role" id="'.$medicalStaff->id.'"><i class="fa fa-edit"></i> Edit</button> &nbsp;';
                }
                if(auth()->user()->hasPermissionTo('delete medical staff')) {
                    $action .= '<button class="btn btn-xs btn-danger delete-role" id="' . $medicalStaff->id . '"><i class="fa fa-trash"></i> Delete</a>';
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
//            'clinic'    => 'required',
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
            $medical_staff->firstname = $request->firstname;
            $medical_staff->middlename = $request->middlename;
            $medical_staff->lastname = $request->lastname;
            $medical_staff->mobileNo = $request->mobileNo;
            $medical_staff->address = $request->address;
            $medical_staff->refprovince = $request->province;
            $medical_staff->refcitymun = $request->city;
            $medical_staff->status = 'offline';
            $medical_staff->category = 'client';
            $medical_staff->assignRole('medical staff');
            foreach ($request->position as $role)
            {
                $medical_staff->assignRole($role);
            }

            if($medical_staff->save())
            {
                return response()->json(['success' => true]);
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

    private function checkInternetConnection()
    {
        $result = 1;
        if(!$sock = @fsockopen('doctorapp.devouterbox.com', 80))
        {
           $result = 0;
        }

        return $result;
    }


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
}
