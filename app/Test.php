<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Test extends Model
{
    protected $table = 'tests';
    public $primaryKey = 'id';
    public $timestamps = true;

    public function task(){
        return $this->belongsTo('App\Task');
    }

    public function size($ext){
        return Storage::size("tests/$this->id.$ext");
    }

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::deleting(function ($test) {
            foreach ($test->runs as $run) {
                $run->delete();
            }
        });
    }
}
