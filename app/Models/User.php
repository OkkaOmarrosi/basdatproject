<?php

namespace App\Models;

use App\Models\Thrubus\Pool;
use App\Models\Thrubus\Mitra;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Notifications\Notifiable;


class User extends Authenticatable
{
    protected $connection = 'mysql';
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function pool()
    {
        return $this->belongsTo(Pool::class, 'id', 'idpool');
    }

    public function mitra()
    {
        return $this->belongsTo(Mitra::class, 'id', 'idmitra');
    }
}
