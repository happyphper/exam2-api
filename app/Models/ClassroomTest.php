<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ClassroomTest extends Pivot
{
    protected $table = 'classroom_tests';

    public $incrementing = false;

    public $timestamps = false;

    public $fillable = ['test_id',];

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    public function test()
    {
        return $this->belongsTo(Test::class);
    }
}
