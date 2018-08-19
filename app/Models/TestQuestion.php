<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Jedrzej\Searchable\SearchableTrait;
use Jedrzej\Sortable\SortableTrait;

class TestQuestion extends Pivot
{
    use SearchableTrait,SortableTrait;
    public $searchable = ['*'];
    public $sortable = ['*'];

    protected $table = 'test_questions';

    protected $fillable = ['test_id', 'question_id'];

    public $incrementing = false;

    public $timestamps = false;
}
