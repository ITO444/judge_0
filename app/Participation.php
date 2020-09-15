<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Participation extends Model
{
    protected $table = 'participations';
    public $primaryKey = 'id';
    public $timestamps = true;
    public function contest(){
        return $this->belongsTo('App\Contest');
    }
    public function user(){
        return $this->belongsTo('App\User');
    }
}
