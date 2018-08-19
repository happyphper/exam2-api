<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Record extends Model
{
    protected $fillable = [
        'user_id',
        'test_id',
        'answer',
        'error_count',
        'correct_count',
        'grade',
    ];

    protected $casts = [
        'answer' => 'array'
    ];
}
