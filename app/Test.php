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

    public function readableSize($ext){
        $size = $this->size($ext);
        $unit = 'B';
        if ($size >= 1024) {
            $size /= 1024;
            $unit = 'KB';
            if ($size > 1024) {
                $size /= 1024;
                $unit = 'MB';
            }
        }
        $size = round($size);
        return "$size $unit";
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
