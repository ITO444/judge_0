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
}
