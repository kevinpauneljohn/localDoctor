<?php

namespace App;

use App\Events\UserCreated;
use App\Traits\UsesUuid;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes, HasRoles, LogsActivity;
    use UsesUuid, HasApiTokens;

//    protected $dispatchesEvents = [
//        'created'   => UserCreated::class
//    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'firstname',
        'middlename',
        'lastname',
        'username',
        'email',
        'password',
        'mobileNo',
        'landline',
        'birthday',
        'address',
        'refregion',
        'refprovince',
        'refcitymun',
        'postalcode',
        'category',
    ];

    protected static $logAttributes = [
        'firstname',
        'middlename',
        'lastname',
        'username',
        'email',
        'password',
        'mobileNo',
        'landline',
        'birthday',
        'address',
        'refregion',
        'refprovince',
        'refcitymun',
        'postalcode',
        'category',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function clinics()
    {
        return $this->belongsToMany(User::class,'clinic_user');
    }
}
