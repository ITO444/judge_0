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
        $end = $time->copy()->addSeconds($this->duration);
        return $user->participations->where('start', '<', $end)->where('end', '>', $start)->isNotEmpty();
    }

    public function canSeeSubmissions(User $user){
        $level = $user->level;
        $participation = $this->participationOf($user);
        $contestNow = $user->contestNow();
        if($participation == null){
            return false;
        }
        if($contestNow != null){
            if($contestNow->id != $participation->id){
                return false;
            }
        }elseif($level < $this->view_level || $this->end > Carbon::now()){
            return false;
        }
        return true;
    }

    public function canSeeResults(User $user){
        $user = auth()->user();
        $level = $user->level;
        $participation = $user->contestNow();
        if($participation !== null){
            if($participation->contest_id != $this->id){
                return false;
            }
        }elseif($this->isUpcoming() || !$this->published || $level < $this->view_level){
            return false;
        }
        if($this->results > Carbon::now() && ($level < $this->edit_level || ($level == 5 && $this->edit_level == 4) || $participation !== null)){
            return $this->feedback();
        }
        return true;
    }

    public function canSeeCon(User $user){
        $participation = $this->participationOf($user);
        $contestNow = $user->contestNow();
        if($contestNow !== null){
            if($participation === $contestNow){
                return true;
            }
            return false;
        }
        return $this->hasEnded();
    }

    public function maxScore(Task $task){
        $taskConfig = $this->tasksConfig()[$task->id];
        return array_sum($this->configuration['tasks'][$task->id]['subtasks']);
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
