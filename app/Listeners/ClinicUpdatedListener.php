<?php

namespace App\Listeners;

use App\Events\ClinicUpdatedEvent;
use App\Threshold;
use GuzzleHttp\Client;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ClinicUpdatedListener
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
     * @param  ClinicUpdatedEvent  $event
     * @return void
     */
    public function handle(ClinicUpdatedEvent $event)
    {
        if($sock = @fsockopen('doctorapp.devouterbox.com', 80))
        {
            $client = new Client([
                'headers' => [
                    'content-type' => 'application/json',
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer '.auth()->user()->api_token,
                ],
            ]);

            $response = $client->request('POST','https://doctorapp.devouterbox.com/api/edit-clinic',[
                'json' => [
                    'id'         => $event->clinic->id,
                    'name'       => $event->clinic->name,
                    'address'    => $event->clinic->address,
                    'state'      => $event->clinic->state,
                    'city'       => $event->clinic->city,
                    'landline'   => $event->clinic->landline,
                    'mobile'     => $event->clinic->mobile,
                    'user_id'    => $event->clinic->user_id,
                    'status'     => $event->clinic->status,
                    'created_at' => date('Y-m-d h:i:s', strtotime($event->clinic->created_at)),
                    'updated_at' => date('Y-m-d h:i:s', strtotime($event->clinic->updated_at)),
                    'terminal_id' => config('terminal.license'),
                    'action'    => 'updated'
                ],
            ]);

            //return json_decode($response->getBody());
            return 1;
        }else{
            $threshold = new Threshold();
            $threshold->causer_id = auth()->user()->id;
            $threshold->table = 'clinics';
            $threshold->data = $event->clinic;
            $threshold->action = "updated";
            $threshold->save();
            return 0;
        }
    }
}
