<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Jedrzej\Searchable\SearchableTrait;
use Jedrzej\Sortable\SortableTrait;

class Test extends Model
{
    use SearchableTrait, SortableTrait;

    public $searchable = ['title', 'type', 'started_at', 'ended_at', 'groups:name', 'groups:id'];

    public $sortable = ['*'];

    public $incrementing = true;

    protected $fillable = [
        'title',
        'type',
        'started_at',
        'ended_at'
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

    public function setStartedAtAttribute($value)
    {
        $this->attributes['started_at'] = Carbon::parse($value)->tz(config('app.timezone'))->toDateTimeString();
    }

    public function setEndedAtAttribute($value)
    {
        $this->attributes['ended_at'] = Carbon::parse($value)->tz(config('app.timezone'))->toDateTimeString();
    }
}
