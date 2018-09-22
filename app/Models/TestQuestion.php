<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class TestQuestion extends Pivot
{
    protected $table = 'test_questions';

    protected $fillable = ['test_id', 'question_id'];

    public $incrementing = false;

    public $timestamps = false;

    public function test()
    {
        return $this->belongsTo(Test::class);
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
