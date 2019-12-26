<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\DataTables;

class RolesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.Roles.index');
    }

    /**
     * Dec. 08, 2019
     * @author john kevin paunel
     * display all roles
     * */
    public function rolesList()
    {
        $roles = \App\Role::all();

        return DataTables::of($roles)
            ->addColumn('counter',function(){
                return "";
            })
            ->addColumn('action', function ($role) {
                $action = "";
                if(auth()->user()->hasPermissionTo('edit role'))
                {
                    $action .= '<button class="btn btn-xs btn-primary edit-role" id="'.$role->id.'"><i class="fa fa-edit"></i> Edit</button> &nbsp;';
                }
                if(auth()->user()->hasPermissionTo('delete role')) {
                    $action .= '<button class="btn btn-xs btn-danger delete-role" id="' . $role->id . '"><i class="fa fa-trash"></i> Delete</a>';
                }

                return $action;
            })
            ->rawColumns(['action'])
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
            'role' => 'required|unique:roles,name'
        ]);

        if($validator->passes())
        {
            Role::create(['name' => $request->role]);

            return response()->json(['success' => true]);
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
        $validator = Validator::make($request->all(),[
            'editRole' => 'required||unique:roles,name'
        ],[
            'editRole.unique' => $request->editRole.' has been already taken'
        ]);

        if($validator->passes())
        {
            $role = Role::find($id);
            $role->name = $request->editRole;
            if($role->save())
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
        $role = \App\Role::find($id);
        if($role->delete())
        {
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false]);
    }
}
