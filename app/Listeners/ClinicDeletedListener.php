<?php

namespace App\Listeners;

use App\Events\ClinicDeletedEvent;
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
        //
    }
}
