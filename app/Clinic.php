<?php

namespace App;

use App\Events\ClinicCreatedEvent;
use App\Events\CreateClinicEvent;
use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Clinic extends Model
{
    use SoftDeletes;
    use UsesUuid;

    protected $guarded = [];

//    protected $dispatchesEvents = [
//        'created'   => ClinicCreatedEvent::class
//    ];

    public function users()
    {
        return $this->belongsToMany(Clinic::class,'clinic_user');
    }
}
