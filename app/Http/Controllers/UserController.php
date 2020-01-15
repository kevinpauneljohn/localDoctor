<?php

namespace App\Http\Controllers;

use App\Threshold;
use App\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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

    public function test()
    {
        $client = new Client([
            'headers' => [
                'content-type' => 'application/json',
                'Accept' => 'application/json',
                'Authorization' => 'Bearer '.auth()->user()->api_token,
            ],
        ]);

//        $response = $client->request('POST','https://doctorapp.devouterbox.com/api/create-clinic',[
//            'json' => [
//                'id'         => ''
//                'name'       => "test ulet",
//                'landline'   => "12456",
//                'mobile'     => "09218173000",
//                'address'    => "bulaon",
//                'state'      => "0516",
//                'city'       => "051605",
//                'user_id'    => auth()->user()->id,
//                'status'     => "active",
//            ],
//        ]);

        //return response()->json(['success' => true,'body' => json_decode($response->getBody())]);
        //return $response->getBody();

    }

//    public function sendToServer($causer_id, $terminal_id, $data, $action, $created_at, $updated_at)
//    {
//        //internet connection ok
//        //API callback
//        $userToken = User::findOrFail($causer_id)->api_token;
//        $client = new Client([
//            'headers' => [
//                'Accept' => 'application/json',
//                'Authorization' => 'Bearer '.$userToken,
//            ],
//        ]);
//
//        $response = $client->request('GET','https://doctorapp.devouterbox.com/api/threshold',[
//        //$response = $client->request('GET','http://outerboxpro.com/api/threshold',[
//            'json' => [
//                'causer_id' => $causer_id,
//                'terminal_id'   => $terminal_id,
//                'data'  => $data,
//                'action'    => $action,
//                'created_at'    => $created_at,
//                'updated_at'    => $updated_at
//            ],
//        ]);
//
////        return response()->json(['success' => true,'body' => json_decode($response->getBody())]);
//        return json_decode($response->getBody());
//    }
}

