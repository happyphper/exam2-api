<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Jedrzej\Searchable\SearchableTrait;
use Jedrzej\Sortable\SortableTrait;

class Record extends Model
{
    use SearchableTrait,SortableTrait;
    public $searchable = ['*'];
    public $sortable = ['*'];

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
