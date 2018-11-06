<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Jedrzej\Searchable\SearchableTrait;
use Jedrzej\Sortable\SortableTrait;

class TestResult extends Model
{
    use SearchableTrait,SortableTrait;
    public $searchable = [
        'test_id',
        'classroom_id',
        'test:title',
        'classroom:name',
        'user:name'
    ];
    public $sortable = ['*'];

    protected $guarded = ['id'];

    protected $casts = [
        'answer' => 'array'
    ];

    public function test()
    {
        return $this->belongsTo(Test::class);
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
