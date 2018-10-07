<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Jedrzej\Searchable\SearchableTrait;
use Jedrzej\Sortable\SortableTrait;

class Question extends Model
{
    use SearchableTrait,SortableTrait;
    public $searchable = ['title', 'type'];
    public $sortable = ['*'];

    protected $fillable = [
        'title',
        'type',
        'options',
        'answer',
        'explain',
        'course_id',
    ];

    protected $casts = [
        'options' => 'array',
        'answer' => 'array'
    ];

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
     * 课程
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function setOptionsAttribute($value)
    {
        foreach ($value as $index => $option) {
            $value[$index]['status'] = 0;
        }
        $this->attributes['options'] = $value;
    }
}
