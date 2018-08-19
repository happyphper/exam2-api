<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
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
