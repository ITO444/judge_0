<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Participation extends Model
{
    protected $table = 'participations';
    public $primaryKey = 'id';
    public $timestamps = true;

    protected $casts = [
        'information' => 'array',
    ];

    public function contest(){
        return $this->belongsTo('App\Contest');
    }

    public function user(){
        return $this->belongsTo('App\User');
    }
    
    public function submissions(){
        return $this->hasMany('App\Submission');
    }
    
    public function isOngoing(){
        $now = Carbon::now();
        return $this->start <= $now && $this->end > $now;
    }

    public function isUpcoming(){
        $now = Carbon::now();
        return $this->start > $now;
    }
}
