<?php

namespace App\Providers;

use App\Events\UserCreated;
use App\Listeners\UserCreatedListener;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
//        Registered::class => [
//            SendEmailVerificationNotification::class,
//        ],
        UserCreated::class => [
            UserCreatedListener::class
        ],
        'App\Events\CreateMedicalStaffEvent' => [
            'App\Listeners\CreateMedicalStaffListener',
        ],
        'App\Events\ClinicCreatedEvent' => [
            'App\Listeners\ClinicCreatedListener',
        ],
        'App\Events\ClinicUpdatedEvent' => [
            'App\Listeners\ClinicUpdatedListener',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
