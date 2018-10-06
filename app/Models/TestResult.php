<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Jedrzej\Searchable\SearchableTrait;
use Jedrzej\Sortable\SortableTrait;

class TestResult extends Model
{
    use SearchableTrait,SortableTrait;
    public $searchable = ['*'];
    public $sortable = ['*'];

    protected $guarded = ['id'];

    protected $casts = [
        'answer' => 'array'
    ];
}
