<?php

namespace App\Models;

use App\Traits\OwnTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Jedrzej\Searchable\SearchableTrait;
use Jedrzej\Sortable\SortableTrait;

class Test extends Model
{
    use OwnTrait;
    use SearchableTrait, SortableTrait;

    public $searchable = [
        'title',
        'course_id',
        'started_at',
        'ended_at'
    ];

    public $sortable = ['*'];

    public $incrementing = true;

    protected $fillable = [
        'title',
        'course_id',
        'started_at',
        'ended_at'
    ];

    public $dates = ['started_at', 'ended_at'];

    public function questions()
    {
        return $this->belongsToMany(Question::class, 'test_questions')->withPivot('score');
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class, 'group_tests');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'model_has_category', 'classified_id', 'category_id', 'id', 'id')->where('classified_type', self::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function result()
    {
        return $this->hasOne(TestResult::class);
    }

    public function setStartedAtAttribute($value)
    {
        $this->attributes['started_at'] = Carbon::parse($value)->tz(config('app.timezone'))->toDateTimeString();
    }

    public function setEndedAtAttribute($value)
    {
        $this->attributes['ended_at'] = Carbon::parse($value)->tz(config('app.timezone'))->toDateTimeString();
    }

    public function scopeToday($query)
    {
        $now = now()->toDateTimeString();
        return $query->where('started_at', '<', $now)->where('ended_at', '>', $now);
    }
}
