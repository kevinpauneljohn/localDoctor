<?php

namespace App\Listeners;

use App\Events\CreateMedicalStaffEvent;
use App\Threshold;
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
        $threshold->save();

        $host="facebook.comasf";

        exec("ping -n 4 " . $host, $output, $result);

//        print_r($output);

        return response()->json($result);
    }
}
