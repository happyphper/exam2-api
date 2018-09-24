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
        'wrong_count',
        'right_count',
        'grade',
    ];

    protected $casts = [
        'answer' => 'array'
    ];
}
