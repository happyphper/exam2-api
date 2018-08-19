<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Jedrzej\Searchable\SearchableTrait;
use Jedrzej\Sortable\SortableTrait;

class Question extends Model
{
    use SearchableTrait,SortableTrait;
    public $searchable = ['*'];
    public $sortable = ['*'];

    protected $fillable = [
        'title',
        'options',
        'right_option',
        'category_id',
        'parsing',
    ];

    protected $casts = [
        'options' => 'array'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
