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

    public function done(Task $task){
        return $this->submissions->where('task_id', $task->id)->where('result', 'Accepted')->isNotEmpty();
    }

    public function getRunnerStatusAttribute($value){
        $strings = [
            -4 => '',
            -3 => 'Running',
            -2 => 'Compiling',
            -1 => 'On Queue',
            0 => 'Accepted',
            1 => 'Compilation Error',
            2 => 'Runtime Error',
            3 => 'Time Limit Exceeded',
            4 => 'Wrong Answer',
            5 => 'Partial Score',
            6 => 'Failed',
        ];
        return $strings[$value];
    }

    public function setRunnerStatusAttribute($value){
        $IDs = [
            '' => -4,
            'Running' => -3,
            'Compiling' => -2,
            'On Queue' => -1,
            'Accepted' => 0,
            'Compilation Error' => 1,
            'Runtime Error' => 2,
            'Time Limit Exceeded' => 3,
            'Wrong Answer' => 4,
            'Partial Score' => 5,
            'Failed' => 6,
        ];
        $this->attributes['runner_status'] = $IDs[$value];
    }
}
