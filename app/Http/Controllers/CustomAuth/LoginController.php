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
//        $client = new Client([
//            'headers' => ['content-type' => 'application/json', 'Accept' => 'application/json'],
//        ]);
//
//        $response = $client->request('POST','https://doctorapp.devouterbox.com/api/login',[
//            'json' => [
//                'username' => 'admin',
//                'password' => '123',
//            ],
//        ]);
//
//        $data = $response->getBody();
//        $data = json_decode($data);

        $http = new Client();

//        $response = $http->post('https://doctorapp.devouterbox.com/oauth/token', [
//            'form_params' => [
//                'grant_type' => 'password',
//                'client_id' => '2',
//                'client_secret' => 'J4ORCAWx6LOJfZq8VGjlU0QAiu7xN0E1ryqdwYzF',
//                'username' => 'john@gmail.com',
//                'password' => '123',
//                'scope' => '',
//            ],
//        ]);
//        $data = json_decode((string) $response->getBody(), true);
        $accessToken = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiYzZiZjk1MGE5ZGUyYzBhNDQ3ZTJlNGVlY2FhMjg5NGU5MzJhMTM5YWViZThlYjBkOTY1OTFkMmZkM2MzZGNhZWM1YmNmZTA1MTFmZTY4MmUiLCJpYXQiOjE1Nzc5NzU1MzEsIm5iZiI6MTU3Nzk3NTUzMSwiZXhwIjoxNjA5NTk3OTMxLCJzdWIiOiIzNjUzYjdhYy00ZjM2LTQ0MjUtYWEwZi00Zjg2OGQxNGNlZjIiLCJzY29wZXMiOltdfQ.KNmQZ0725fkzYCUmaXEst_5puTSXW96oCfvzBnINOqIRJI0_KA94jlX4rXjj02XtKvYnEbuIPNC3COPKLSTSzAp6cLwVGsqUkXWAuBLudeM9sKfltpyne6KdOezhSv9HcVoR5Ka-cA681HFqHHEG3UwN16mV-XCjHBovaRn16LeU56FoBetFKpT1XKsPQozgQBOSwLRK3w1veSm0J0nDKbnzAC8Y1W9B_oKalzJLE2sQ4OK88oUuclNyKzIOTuCwcNvqM4MknzQyBgSFXK-Cg_PIrNl3rCuEy95oUHA2vXFR2HG7_Iim8AMVSrRHKTcd0_sbtaM9Vg-i0XAjMUCe5RR1JRDx53lZBZ6JE4Xt5ddeGrnT9lUxV19XHnsXKfEt2H0kSybu8yB9CF50XhU763Rh7cExpQEkzw5e8OgvIOFw1RwgztWmnI0SIF3JS9DcbyJFadZsm21QczXdOscvKg26fu0J5LRGIO0pBq8R_D4unp1tFsNeqzO1Ta49LGSqqP_B1cAyx65LDinNabrA7cSQf7ryysfMqJJFrFMmOuW56w0H_W1-AfiHrQEeuvv0pY91q0-8jYSXDjWL_rm1xu2h8ibRgwspiStObbNCBbyJveNxOaM3flaxMXiIm4JhIKB2kgxO6YXrbqrR8uzkJRkqZ970FsHKO7tm6rcfuNA';

        $response = $http->request('GET', 'https://doctorapp.devouterbox.com/api/user', [
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer '.$accessToken,
            ],
        ]);

        return json_decode((string) $response->getBody(), true);

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
            //this will check if there is internet connectivity
            if(!$sock = @fsockopen('doctorapp.devouterbox.com', 80))
            {
                return back()->with(['success' => false, 'message' => 'No Internet Connection '])->withInput();
            }
            else
            {
                //get the user data from server
                $datas = $this->authenticate_credential($request->username, $request->password);

                if($datas->success === true)
                {
                    $array = array();
                    foreach ($datas->user as $key => $data){
                        $array = [
                            'id'    => $data->id,
                            'firstname'            => $data->firstname,
                            'middlename'           => $data->middlename,
                            'lastname'             => $data->lastname,
                            'username'             => $data->username,
                            'email'                => $data->email,
                            'email_verified_at'    => $data->email_verified_at,
                            'password'             => $data->password,
                            'api_token'            => $data->api_token,
                            'mobileNo'             => $data->mobileNo,
                            'landline'             => $data->landline,
                            'birthday'             => $data->birthday,
                            'address'              => $data->address,
                            'refregion'            => $data->refregion,
                            'refprovince'          => $data->refprovince,
                            'refcitymun'           => $data->refcitymun,
                            'postalcode'           => $data->postalcode,
                            'status'               => $data->status,
                            'created_at'           => $data->created_at,
                            'updated_at'           => $data->updated_at,
                            'deleted_at'           => $data->deleted_at,
                            'category'             => $data->category,
                            'owner'                => $data->owner,
                        ];
                    }
                    //credential validation returns true
                    //save the owners data to local database
                    DB::table('users')->insert($array);
                    $user = User::find($array['id']);
                    //after user data was saved from the local database it will set the user role
                    $user->assignRole($datas->roles);
                }
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
