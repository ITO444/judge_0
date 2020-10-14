<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Run extends Model
{
    protected $table = 'runs';
    public $primaryKey = 'id';
    public $timestamps = true;
    public function submission(){
        return $this->belongsTo('App\Submission');
    }
    public function test(){
        return $this->belongsTo('App\Test');
    }

    public function getResultAttribute($value){
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

    public function setResultAttribute($value){
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
        $this->attributes['result'] = $IDs[$value];
    }
}
