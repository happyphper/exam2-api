<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class GroupTest extends Pivot
{
    protected $table = 'group_tests';

    public $incrementing = false;

    public $timestamps = false;

    public $fillable = ['test_id',];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function test()
    {
        return $this->belongsTo(Test::class);
    }
}
