<?php

namespace App\Http\Controllers;

use http\Client\Response;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Role;
use Yajra\DataTables\DataTables;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return view('pages.Permissions.index')->with([
            'roles' => Role::all()
        ]);
    }

    /**
     * Dec. 08, 2019
     * @author john kevin paunel
     * display all roles
     * */
    public function permissionList()
    {
        $permissions = \App\Permission::all();

        return DataTables::of($permissions)
            ->addColumn('roles',function($permission){
                $permission_roles = Permission::whereName($permission->name)->first()->roles->pluck('name');
                $roles = '<div id="data-cell-'.$permission->id.'">';
                    foreach( $permission_roles as $roleName){
                        $roles .= '<span class="badge badge-primary">'.$roleName.'</span>';
                    }
                    $roles .= '</div>';
                return $roles;
            })
            ->addColumn('action', function ($permission) {
                $action ="";
                if(auth()->user()->hasPermissionTo('assign role to permission'))
                {
                    $action .= '<button class="btn btn-xs btn-success assign-role" id="'.$permission->id.'"><i class="fa fa-id-badge"></i> Assign Roles</button> &nbsp;';
                }

                if(auth()->user()->hasPermissionTo('edit permission'))
                {
                    $action .= '<button class="btn btn-xs btn-primary edit-permission" id="'.$permission->id.'"><i class="fa fa-edit"></i> Edit</button> &nbsp;';
                }
                if(auth()->user()->hasPermissionTo('delete permission'))
                {
                    $action .= '<button class="btn btn-xs btn-danger delete-permission" id="'.$permission->id.'"><i class="fa fa-trash"></i> Delete</a>';
                }

                return $action;
            })
            ->rawColumns(['action','roles'])
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
            'permission' => 'required|unique:permissions,name'
        ]);

        if($validator->passes())
        {
           Permission::create(['name' => $request->permission]);

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
        return Permission::find($id)->roles;
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
            'editPermission' => 'required||unique:permissions,name'
        ],[
            'editPermission.unique' => $request->editPermission.' has been already taken'
        ]);

        if($validator->passes())
        {
            $permission = Permission::find($id);
            $permission->name = $request->editPermission;
            if($permission->save())
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
     * Dec. 09, 2019
     * @author john kevin paunel
     * get roles to permission
     * @param Request $request
     * @return Request
     * */
    public function permissionAssignedRoles(Request $request)
    {
        return $request->roles;
    }
    /**
     * Dec. 09, 2019
     * @author john kevin paunel
     * set roles to permission
     * @param Request $request
     * @return Response
     * */
    public function permissionSetRole(Request $request)
    {
//        /*check if there are roles in a permission*/
        $checkRoles = Permission::find($request->id)->roles->count();

        $permission = Permission::find($request->id);
        if($checkRoles > 0)
        {
            /*remove the old roles*/
                foreach ($permission->roles as $role)
                {
                    $permission->removeRole($role->name);
                }
        }

        /*save the new roles*/
        $permission->assignRole($request->roles);

        return response()->json(
            [
                'success' => true,
                'url' => $request->assignRoleUrl,
                'id' => $request->id,
                'roles' => $request->roles
            ]);

    }
}
