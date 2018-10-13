<?php

namespace App\Models;

use App\Traits\OwnTrait;
use Illuminate\Database\Eloquent\Model;
use Jedrzej\Searchable\SearchableTrait;
use Jedrzej\Sortable\SortableTrait;

class Group extends Model
{
    use OwnTrait;
    use SearchableTrait,SortableTrait;

    public $searchable = ['name'];

    public $sortable = ['*'];

    protected $fillable = ['name'];

    /**
     * 测验
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tests()
    {
        return $this->belongsToMany(Test::class, 'group_tests');
    }


    /**
     * 获取今日测验
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function today()
    {
        $now = now();

        return $this->hasManyThrough(Test::class, 'group_tests', 'test_id', 'id', 'id', 'group_id')
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
