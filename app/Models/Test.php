<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Jedrzej\Searchable\SearchableTrait;
use Jedrzej\Sortable\SortableTrait;

class Test extends Model
{
    use SearchableTrait, SortableTrait;

    public $searchable = [
        'title',
        'type',
        'started_at',
        'ended_at',
        'groups:name',
        'groups:id',
        'categories:name',
        'categories:id',
        'course:title',
        'course_id',
    ];

    public $sortable = ['*'];

    public $incrementing = true;

    protected $fillable = [
        'title',
        'type',
        'started_at',
        'ended_at',
        'course_id',
    ];

    protected $dates = ['started_at', 'ended_at'];

    public function questions()
    {
        return $this->hasManyThrough(Question::class, TestQuestion::class, 'test_id', 'id', 'id', 'question_id');
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class, 'group_tests');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'model_has_category', 'classified_id', 'category_id', 'id', 'id')->where('classified_type', self::class);
        // return $this->morphMany(ModelHasCategory::class,  'classified');
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function setStartedAtAttribute($value)
    {
        $this->attributes['started_at'] = Carbon::parse($value)->tz(config('app.timezone'))->toDateTimeString();
    }

    public function setEndedAtAttribute($value)
    {
        $this->attributes['ended_at'] = Carbon::parse($value)->tz(config('app.timezone'))->toDateTimeString();
    }
}
