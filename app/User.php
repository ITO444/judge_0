<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\SetPassword;
use Illuminate\Support\Facades\Password;
use Carbon\Carbon;

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
        return $this->submissions->where('participation_id', null)->where('task_id', $task->id)->where('result', 'Accepted')->isNotEmpty();
    }

    public function getRunnerStatusAttribute($value){
        $strings = [
            -5 => 'Saved',
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
            'Saved' => -5,
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

    public function getLevelAttribute($value){
        if(auth()->user() && auth()->user()->id == $this->id){
            return min($value, $this->attributes['temp_level']);
        }
        return $value;
    }

    public function contestNow(){
        $now = Carbon::now();
        $participation = $this->participations->where('start', '<=', $now)->where('end', '>', $now)->first();
        return $participation;
    }

    public function sendEmailVerificationNotification()
    {
        $token = Password::broker()->createToken($this);
        $this->notify(new SetPassword($token, $this));
    }

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::deleting(function ($user) {
            foreach ($user->participations as $participation) {
                $participation->delete();
            }
            foreach ($user->submissions as $submission) {
                $submission->delete();
            }
        });
    }
}
