<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contest extends Model
{
    protected $table = 'contests';
    public $primaryKey = 'id';
    public $timestamps = true;
    public function participations(){
        return $this->hasMany('App\Participation');
    }
}
