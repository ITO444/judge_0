<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Contest extends Model
{
    protected $table = 'contests';
    public $primaryKey = 'id';
    public $timestamps = true;

    protected $casts = [
        'configuration' => 'array',
    ];

    public function participations(){
        return $this->hasMany('App\Participation');
    }

    public function submissions(){
        return $this->hasMany('App\Submission');
    }

    public function participationOf(User $user){
        return $this->participations->where('user_id', $user->id)->first();
    }

    public function doneBy(User $user){
        return $this->participationOf($user) != null;
    }

    public function canUnreg(User $user){
        $participation = $this->participationOf($user);
        return $participation != null && $user->level >= $this->reg_level && Carbon::now() < $participation->start;
    }

    public function hasOverlap(User $user, Carbon $time){
        $start = $time;
        $end = $time->addSeconds($this->duration);
        return $user->participations->where('start', '<', $end)->where('end', '>', $start)->isNotEmpty();
    }

    public function isOngoing(){
        $now = Carbon::now();
        return $this->published && $this->start <= $now && $this->end > $now;
    }

    public function isUpcoming(){
        $now = Carbon::now();
        return $this->published && $this->start > $now;
    }

    public function hasEnded(){
        $now = Carbon::now();
        return $this->published && $this->end <= $now;
    }

    public function tasks(){
        $taskArr = array_keys($this->tasksConfig());
        return Task::whereIn('id', $taskArr)->get();
    }

    public function tasksConfig(){
        return $this->configuration["tasks"];
    }

    public function feedback(){
        return $this->configuration["feedback"];
    }

    public function cumulative(){
        return $this->configuration["cumulative"];
    }
}
