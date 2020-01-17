<?php

namespace App\Listeners;

use App\Events\ClinicUpdatedEvent;
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
        //
    }
}
