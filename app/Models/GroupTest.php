<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Jedrzej\Searchable\SearchableTrait;
use Jedrzej\Sortable\SortableTrait;

class GroupTest extends Pivot
{
    use SearchableTrait,SortableTrait;
    public $searchable = ['*'];
    public $sortable = ['*'];

    protected $table = 'group_tests';

    public $incrementing = false;

    public $timestamps = false;
}
