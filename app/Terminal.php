<?php

namespace App;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Terminal extends Model
{
    use SoftDeletes, LogsActivity, UsesUuid;

    protected $fillable = ['user_id','device','description'];
    protected static $logAttributes = ['user_id','device','description'];
}
