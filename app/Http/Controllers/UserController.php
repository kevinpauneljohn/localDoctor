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
        $host="facebook.com";

        exec("ping -n 4 " . $host, $output, $result);

//        return $result;

        if($result === 0)
        {
            $thresholds = Threshold::all();
            foreach ($thresholds as $threshold){
                $server = $this->sendToServer(
                    $threshold->causer_id,
                    config('terminal.license'),
                    $threshold->data,
                    $threshold->action,
                    date('Y-m-d h:i:s', strtotime($threshold->created_at)),
                    date('Y-m-d h:i:s', strtotime($threshold->updated_at))
                );
            }
            if($server === 1)
            {
                return 'success';
            }else{
                return 'failed';
            }
//            return $server;

        }

    }

    public function sendToServer($causer_id, $terminal_id, $data, $action, $created_at, $updated_at)
    {
        //internet connection ok
        //API callback
        $userToken = User::findOrFail($causer_id)->api_token;
        $client = new Client([
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer '.$userToken,
            ],
        ]);

        //$response = $client->request('POST','https://doctorapp.devouterbox.com/api/userClients',[
        $response = $client->request('GET','http://outerboxpro.com/api/threshold',[
            'json' => [
                'causer_id' => $causer_id,
                'terminal_id'   => $terminal_id,
                'data'  => $data,
                'action'    => $action,
                'created_at'    => $created_at,
                'updated_at'    => $updated_at
            ],
        ]);

        //return response()->json(['success' => true,'body' => json_decode($response->getBody())]);
        return json_decode($response->getBody());
    }
}

