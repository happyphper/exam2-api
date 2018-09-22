<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Jedrzej\Searchable\SearchableTrait;
use Jedrzej\Sortable\SortableTrait;

class Group extends Model
{
    use SearchableTrait,SortableTrait;

    public $searchable = ['name'];

    public $sortable = ['*'];

    protected $fillable = ['name'];

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
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function today()
    {
        $now = now();

        return $this->hasManyThrough(Test::class, GroupTest::class, 'test_id', 'id', 'id', 'group_id')
            ->where('started_at', '<', $now)
            ->where('ended_at', '>', $now);
    }

    /**
     * 分类
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function categories()
    {
        return $this->morphMany(ModelHasCategory::class, 'classified');
    }
}
