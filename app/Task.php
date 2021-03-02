<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $table = 'tasks';
    public $primaryKey = 'id';
    public $timestamps = true;

    public function submissions(){
        return $this->hasMany('App\Submission');
    }

    public function tests(){
        return $this->hasMany('App\Test');
    }

    public function contests(){
        return $this->belongsToMany('App\Contest');
    }

    public function doneBy(User $user){
        return $this->submissions->where('participation_id', null)->where('user_id', $user->id)->where('result', 'Accepted')->isNotEmpty();
    }

    public function getGraderStatusAttribute($value){
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

    public function setGraderStatusAttribute($value){
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
        $this->attributes['grader_status'] = $IDs[$value];
    }

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::deleting(function ($task) {
            foreach ($task->submissions as $submission) {
                $submission->delete();
            }
            foreach ($task->tests as $test) {
                $test->delete();
            }
        });
    }
}
