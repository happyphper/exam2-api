<?php

namespace App\Models;

use App\Traits\ShareTrait;
use Illuminate\Database\Eloquent\Model;
use Jedrzej\Searchable\SearchableTrait;
use Jedrzej\Sortable\SortableTrait;

class Question extends Model
{
    use SearchableTrait, SortableTrait;
    use ShareTrait;
    public $searchable = [
        'title',
        'type',
        'chapter',
        'section',
        'course:title',
    ];
    public $sortable   = ['*'];

    protected $fillable = [
        'title',
        'image',
        'type',
        'options',
        'answer',
        'explain',
        'course_id',
        'chapter',
        'section',
    ];

    protected $casts = [
        'options' => 'array',
        'answer'  => 'array',
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

    public function result()
    {
        return $this->belongsTo(QuestionResult::class);
    }

    public function getImageAttribute($value)
    {
        return $value ? config('filesystems.disks.qiniu.domains.default') . '/' . $value : null;
    }

    public function setAnswerAttribute($value)
    {
        $value = is_string($value) ? json_decode($value, true) : $value;

        $answer = collect($value)->map(function ($item) {
            return (int)$item;
        })->sort()->values()->toJson();

        $this->attributes['answer'] = $answer;
    }

    public function getOptionsAttribute($value)
    {
        $options = json_decode($value, true);
        $options = collect($options)->map(function ($item) {
            $content = $item['type'] === 'image' ? config('filesystems.disks.qiniu.domains.default') . '/' . $item['content'] : null;
            return [
                'id'      => $item['id'],
                'content' => $content,
                'type'    => $item['type'],
                'status'  => 0,
            ];
        })->toArray();
        return $options;
    }
}
