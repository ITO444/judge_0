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
}
