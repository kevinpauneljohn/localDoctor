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
        $threshold = new Threshold();
        $threshold->causer_id = auth()->user()->id;
        $threshold->data = $obj->merge($event->clinics);
        $threshold->action = "created medical staff";

        if($threshold->save())
        {
            if($this->ping("outerboxpro.com") === 0)
            {
                /*this will retrieve all rows from thresholds table*/
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

                    $success = "0";
                    /*will return 1 if the transfer was success*/
                    if($server === 1)
                    {
                        /*will delete the rows if the data was transferred successfully*/
                        $thresholdTrash = Threshold::find($threshold->id);
                        $thresholdTrash->delete();
                        $success = "1";
                    }
                }
            }
        }
    }

    /**
     * Jan. 08, 2020
     * @author john kevin paunel
     * check the internet connection and status of the server
     * @param string $host
     * @return string
     * */
    public function ping($host)
    {
        /*this will check if the connection is good*/
        //$host="outerboxpro.com";

        exec("ping -n 4 " . $host, $output, $result);
        return $result;
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

        return json_decode($response->getBody());
    }
}
