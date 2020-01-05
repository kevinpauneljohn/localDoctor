<?php

namespace App\Listeners;

use App\Events\UserCreated;
use App\Role;
use App\Threshold;
use App\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class UserCreatedListener
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
     * @param  UserCreated  $event
     * @return void
     */
    public function handle(UserCreated $event)
    {
//        $object = array_push($event->user, $event->user->getRoleNames());
//        $object = [
//            "firstname"  => $event->user,
//            "hello" => "world"
//        ];
        $obj = new Collection(json_decode($event->user));
        $roles = DB::table('model_has_roles')
            ->join('roles','model_has_roles.role_id','=','roles.id')
            ->select('roles.name as role_name')
            ->where('model_has_roles.model_id',$event->user->id)->get();
        $threshold = new Threshold();
        $threshold->causer_id = auth()->user()->id;
        ///$threshold->data = $obj->merge(json_decode($event->user->getRoleNames()));
        $threshold->data = DB::table('model_has_roles')->get();
        $threshold->action = "created medical staff";
        $threshold->save();
    }
}
