<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Jedrzej\Searchable\SearchableTrait;
use Jedrzej\Sortable\SortableTrait;

class Category extends Model
{
    use SearchableTrait,SortableTrait;
    public $searchable = ['*'];
    public $sortable = ['*'];

    protected $fillable = ['type', 'name', 'parent_id'];

    public function questions()
    {
        return $this->hasMany(Question::class);
    }
}
