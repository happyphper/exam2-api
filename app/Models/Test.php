<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    public $incrementing = true;

    protected $fillable = [
        'title',
        'type',
    ];

    public function questions()
    {
        return $this->hasManyThrough(Question::class, TestQuestion::class, 'test_id', 'id', 'id', 'question_id');
    }
}
