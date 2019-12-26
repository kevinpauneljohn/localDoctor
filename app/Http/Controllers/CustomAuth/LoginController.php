<?php

namespace App\Http\Controllers\CustomAuth;

use App\Http\Controllers\Controller;
use App\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Models\Activity;

class LoginController extends Controller
{
    /**
     * Dec. 26, 2019
     * @author john kevin paunel
     * this will check if there are users in the database. If there are no users
     * this will fetch the data of the owner from the server
     * */
    private function checkUsers()
    {
        return User::all()->count();
    }

    public function forTest()
    {
        $client = new Client([
            'headers' => ['content-type' => 'application/json', 'Accept' => 'application/json'],
        ]);

        $response = $client->request('POST','https://doctorapp.devouterbox.com/api/login',[
            'json' => [
                'username' => 'admin',
                'password' => '123',
            ],
        ]);

        $data = $response->getBody();
        $data = json_decode($data);



    }
    /**
     * Dec. 27, 2019
     * @author john kevin paunel
     * authenticate the credential via external api
     * @param string $username'
     * @param string $password
     * @return object
     * */
    private function authenticate_credential($username, $password)
    {
        $client = new Client([
            'headers' => ['content-type' => 'application/json', 'Accept' => 'application/json'],
        ]);

        $response = $client->request('POST','https://doctorapp.devouterbox.com/api/login',[
            'json' => [
                'username' => $username,
                'password' => $password,
            ],
        ]);

        $data = $response->getBody();
        $data = json_decode($data);
        return $data;

    }

    /**
     * Dec. 06, 2019
     * @author john kevin paunel
     * display custom login form
     * route: login
     * */
    public function login()
    {
        if(Auth::check())
        {
            return redirect(route('dashboard'));
        }
        return view('vendor.adminlte.login');
    }

    /**
     * Dec. 06, 2019
     * @author john kevin paunel
     * authenticated the username and password submitted
     * route: login.authenticate
     * @param Request $request
     * @return mixed
     * */
    public function authenticate(Request $request)
    {
        $request->validate([
            'username'      => 'required',
            'password'      => 'required',
        ],[
            'username.required' => 'Username is required',
            'password.required' => 'Password is required',
        ]);

        /*this will check if there are users save in the database
        if less than 1 meaning the Users table is empty*/
        if($this->checkUsers() < 1)
        {
            //get the user data from server
            $datas = $this->authenticate_credential($request->username, $request->password);

            if($datas->success === true)
            {
                $array = array();
                foreach ($datas->user as $key => $data){
                    $array = [
                        'id'    => $data->id,
                        'firstname'    => $data->firstname,
                        'middlename'    => $data->middlename,
                        'lastname'    => $data->lastname,
                        'username'    => $data->username,
                        'email'    => $data->email,
                        'email_verified_at'    => $data->email_verified_at,
                        'password'    => $data->password,
                        'mobileNo'    => $data->mobileNo,
                        'landline'    => $data->landline,
                        'birthday'    => $data->birthday,
                        'address'    => $data->address,
                        'refregion'    => $data->refregion,
                        'refprovince'    => $data->refprovince,
                        'refcitymun'    => $data->refcitymun,
                        'postalcode'    => $data->postalcode,
                        'status'    => $data->status,
                        'created_at'    => $data->created_at,
                        'updated_at'    => $data->updated_at,
                        'deleted_at'    => $data->deleted_at,
                        'category'    => $data->category,
                        'owner'    => $data->owner,
                    ];
                }
                //credential validation returns true
                //save the owners data to local database
                DB::table('users')->insert($array);
            }
        }

        $credential = $request->only('username','password');

        if(Auth::attempt($credential))
        {
            activity()->causedBy(auth()->user()->id)->withProperties(['username' => auth()->user()->username])->log('user logged in');
            return redirect(route('dashboard'));
        }
        return back()->with(['success' => false, 'message' => 'Invalid Credential'])->withInput();
    }

    /**
     * Dec. 06, 2019
     * @author john kevin paunel
     * @param Request $request
     * @return mixed
     * */
    public function logout(Request $request)
    {
        activity()->causedBy(auth()->user()->id)->withProperties(['username' => auth()->user()->username])->log('user logged out');
        Auth::logout();

        return redirect(route('login'));
    }


}
