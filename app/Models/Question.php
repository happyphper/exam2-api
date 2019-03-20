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

    public function setImageAttribute($value)
    {
        $domain = config('filesystems.disks.qiniu.domains.default');
        $this->attributes['image'] = $value && str_contains($value, $domain) ? str_after($value, $domain) : $value;
    }


    public function getImageAttribute($value)
    {
        return $value ? config('filesystems.disks.qiniu.domains.default') . '/' . $value : null;
    }

    public function setAnswerAttribute($value)
    {
        if (is_string($value)) {
            $value = $this->transformLetterToOptionNumber($value);
        } else if (is_array($value)) {
            $value = array_map(function ($item) {
                return $this->transformLetterToOptionNumber($item);
            }, $value);
        }

        $answer = collect($value)->map(function ($item) {
            return (int)$item;
        })->sort()->values()->toJson();

        $this->attributes['answer'] = $answer;
    }

    public function setOptionsAttribute($value)
    {
        $domain = config('filesystems.disks.qiniu.domains.default');

        $options = collect($value)->map(function ($item) use ($domain) {
            if ($item['type'] === 'image') {
                $content = str_contains($item['content'], $domain)
                    ? trim(str_after($item['content'], $domain), '/')
                    : $item['content'];
            } else {
                $content = $item['content'];
            }
            return [
                'id'      => $item['id'],
                'content' => $content,
                'type'    => $item['type']
            ];
        })->toJson();

        $this->attributes['options'] = $options;
    }

    public function getOptionsAttribute($value)
    {
        $options = json_decode($value, true);
        $options = collect($options)->map(function ($item) {
            if ($item['type'] === 'image') {
                $content = config('filesystems.disks.qiniu.domains.default') . '/' . $item['content'];
            } else {
                $content = $item['content'];
            }
            return [
                'id'      => $item['id'],
                'content' => $content,
                'type'    => $item['type'],
                'status'  => 0,
            ];
        })->toArray();
        return $options;
    }

    /**
     * 字母转换为选项
     *
     * @param $letter
     *
     * @return int
     */
    public function transformLetterToOptionNumber(string $letter)
    {
        if (in_array($letter, range('A', 'Z'))) {
            return ord($letter) - 64;
        }

        if (in_array($letter, range('a', 'z'))) {
            return ord($letter) - 96;
        }

        if (is_numeric($letter)) {
            return $letter;
        }

        return 1;
    }
}
