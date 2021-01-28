<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    protected $table = 'submissions';
    public $primaryKey = 'id';
    public $timestamps = true;
    public function user(){
        return $this->belongsTo('App\User');
    }
    public function task(){
        return $this->belongsTo('App\Task');
    }
    public function runs(){
        return $this->hasMany('App\Run');
    }
    public function contest(){
        return $this->belongsTo('App\Contest');
    }

    public function getResultAttribute($value){
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

    public function setResultAttribute($value){
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
        $this->attributes['result'] = $IDs[$value];
    }
}
