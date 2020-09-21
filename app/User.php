<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'real_name', 'display', 'level', 'google_id', 'email', 'password', 'avatar', 'avatar_original', 'runner_status', 'answer'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'google_id', 'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $table = 'users';
    public $primaryKey = 'id';
    public $timestamps = true;
    
    public function submissions(){
        return $this->hasMany('App\Submission');
    }
    public function participations(){
        return $this->hasMany('App\Participation');
    }
}
