<?php

namespace App\Listeners;

use App\Events\CreateMedicalStaffEvent;
use App\Threshold;
use App\User;
use GuzzleHttp\Client;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;

class CreateMedicalStaffListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  CreateMedicalStaffEvent  $event
     * @return void
     */
    public function handle(CreateMedicalStaffEvent $event)
    {
        $obj = new Collection($event->medicalStaff);
        $medicalStaff = $obj->merge($event->clinics);
        //there is internet connection
        if($sock = @fsockopen('doctorapp.devouterbox.com', 80))
        {

            $client = new Client([
                'headers' => [
                    'content-type' => 'application/json',
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer '.auth()->user()->api_token,
                ],
            ]);

            $response = $client->request('POST','https://doctorapp.devouterbox.com/api/create-medical-staff',[
                'json' => [
                    'clinic_user_id'         => $medicalStaff['id'],
                    'firstname'       => $medicalStaff['firstname'],
                    'middlename'    => $medicalStaff['middlename'],
                    'lastname'      => $medicalStaff['lastname'],
                    'mobileNo'       => $medicalStaff['mobileNo'],
                    'address'   => $medicalStaff['address'],
                    'refprovince'     => $medicalStaff['refprovince'],
                    'refcitymun'    => $medicalStaff['refcitymun'],
                    'status'     => "offline",
                    'category'     => "client",
                    'created_at' => date('Y-m-d h:i:s', strtotime($medicalStaff['created_at'])),
                    'updated_at' => date('Y-m-d h:i:s', strtotime($medicalStaff['updated_at'])),
                    'roles'     => $medicalStaff['roles'],
                    'clinic_id'     => $medicalStaff['clinic_id'],
                    'user_id'     => $medicalStaff['user_id'],
                    'terminal_id' => config('terminal.license'),
                    'action'    => 'created',
                    'causer_id' => auth()->user()->id
                ],
            ]);

            return json_decode($response->getBody());
        }else{
            $threshold = new Threshold();
            $threshold->causer_id = auth()->user()->id;
            $threshold->table = "medicalStaff";
            $threshold->data = $medicalStaff;
            $threshold->action = "created";
            $threshold->save();
            return 0;
        }


        ///return 1;
//        return $medicalStaff['clinic_id'];
    }

    /**
     * Jan. 08, 2020
     * @author john kevin paunel
     * api endpoint for transfering rows from threshold table origin local to server destination
     * @param  string $causer_id
     * @param string $terminal_id
     * @param string $data
     * @param string $action
     * @param string $created_at
     * @param string $updated_at
     * @return mixed
     * */
    public function sendToServer($causer_id, $terminal_id, $data, $action, $created_at, $updated_at)
    {
        //internet connection ok
        //API callback
        $userToken = User::findOrFail($causer_id)->api_token;
        $client = new Client([
            'headers' => [
                'content-type' => 'application/json',
                'Accept' => 'application/json',
                'Authorization' => 'Bearer '.$userToken,
            ],
        ]);

        $response = $client->request('POST','https://doctorapp.devouterbox.com/api/threshold',[
            'json' => [
                'causer_id'     => $causer_id,
                'terminal_id'   => $terminal_id,
                'data'          => $data,
                'action'        => $action,
                'created_at'    => $created_at,
                'updated_at'    => $updated_at
            ],
        ]);

        return json_decode($response->getBody());
    }
}
