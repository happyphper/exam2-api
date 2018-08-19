<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $fillable = ['name', 'category_id'];

    public function tests()
    {
        return $this->hasManyThrough(Test::class, GroupTest::class, 'test_id', 'id', 'id','group_id');
    }
}
