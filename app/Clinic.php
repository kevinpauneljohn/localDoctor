<?php

namespace App;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Clinic extends Model
{
    use SoftDeletes;
    use UsesUuid;

    protected $guarded = [];

    public function users()
    {
        return $this->belongsToMany(Clinic::class);
    }
}
