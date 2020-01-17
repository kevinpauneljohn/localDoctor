<?php

namespace App\Listeners;

use App\Events\ClinicDeletedEvent;
use App\Threshold;
use GuzzleHttp\Client;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ClinicDeletedListener
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
     * @param  ClinicDeletedEvent  $event
     * @return void
     */
    public function handle(ClinicDeletedEvent $event)
    {
        //no internet connection or the server is not online
        if($sock = @fsockopen('doctorapp.devouterbox.com', 80))
        {
            $client = new Client([
                'headers' => [
                    'content-type' => 'application/json',
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer '.auth()->user()->api_token,
                ],
            ]);

            $response = $client->request('POST','https://doctorapp.devouterbox.com/api/delete-clinic',[
                'json' => [
                    'id'         => $event->clinic_id,
                    'name'       => "",
                    'address'    => "",
                    'state'      => "",
                    'city'       => "",
                    'landline'   => "",
                    'mobile'     => "",
                    'user_id'    => auth()->user()->id,
                    'status'     => "Inactive",
                    'created_at' => null,
                    'updated_at' => null,
                    'terminal_id' => config('terminal.license'),
                    'action'    => 'deleted'
                ],
            ]);

            return 1;
        }else{
            $array = array(
                'id'         => $event->clinic_id,
                'name'       => "",
                'address'    => "",
                'state'      => "",
                'city'       => "",
                'landline'   => "",
                'mobile'     => "",
                'user_id'    => auth()->user()->id,
                'status'     => "Inactive",
                'created_at' => null,
                'updated_at' => null,
            );
            $threshold = new Threshold();
            $threshold->causer_id = auth()->user()->id;
            $threshold->table = 'clinics';
            $threshold->data = json_encode($array);
            $threshold->action = "deleted";
            $threshold->save();
            return 0;
        }
    }
}
