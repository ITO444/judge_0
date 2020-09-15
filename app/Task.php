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
}
