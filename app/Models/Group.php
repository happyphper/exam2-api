<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $fillable = ['name', 'category_id'];

    /**
     * 测验
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function tests()
    {
        return $this->hasManyThrough(Test::class, GroupTest::class, 'test_id', 'id', 'id','group_id');
    }


    /**
     * 获取今日测验
     */
    public function today()
    {
        $now = now();

        return $this->hasManyThrough(Test::class, GroupTest::class, 'test_id', 'id', 'id', 'group_id')
            ->where('started_at', '<', $now)
            ->where('ended_at', '>', $now);
    }
}
