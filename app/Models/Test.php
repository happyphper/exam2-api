<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Jedrzej\Searchable\SearchableTrait;
use Jedrzej\Sortable\SortableTrait;

class Test extends Model
{
    use SearchableTrait,SortableTrait;
    public $searchable = ['*'];
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
}
