<?php

namespace App\Models;

use App\Traits\OwnTrait;
use App\Traits\ShareTrait;
use Illuminate\Database\Eloquent\Model;
use Jedrzej\Searchable\SearchableTrait;
use Jedrzej\Sortable\SortableTrait;

class Classroom extends Model
{
    use OwnTrait, ShareTrait;
    use SearchableTrait,SortableTrait;

    public $searchable = ['title'];

    public $sortable = ['*'];

    protected $fillable = ['title'];

    /**
     * 测验
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function exams()
    {
        return $this->belongsToMany(Exam::class, 'classroom_exams');
    }


    /**
     * 获取今日测验
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function today()
    {
        $now = now();

        return $this->hasManyThrough(Exam::class, 'classroom_exams', 'exam_id', 'id', 'id', 'classroom_id')
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

    /**
     * 用户
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
