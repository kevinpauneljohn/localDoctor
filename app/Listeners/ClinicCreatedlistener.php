<?php

namespace App\Listeners;

use App\Threshold;
use App\User;
use GuzzleHttp\Client;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\ClinicCreatedEvent;

class ClinicCreatedlistener
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
     * @param  object  $event
     * @return void
     */
    public function handle(ClinicCreatedEvent $event)
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

            $response = $client->request('POST','https://doctorapp.devouterbox.com/api/create-clinic',[
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
                ],
            ]);

            //return response()->json(['success' => true,'body' => json_decode($response->getBody())]);
//            return $response->getBody();
        }else{
            $threshold = new Threshold();
            $threshold->causer_id = auth()->user()->id;
            $threshold->data = $event->clinic;
            $threshold->action = "created clinic";
            $threshold->save();
        }
    }
}
