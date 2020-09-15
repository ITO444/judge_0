<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    protected $table = 'tests';
    public $primaryKey = 'id';
    public $timestamps = true;
    public function task(){
        return $this->belongsTo('App\Task');
    }
}
