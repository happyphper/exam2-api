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
        'type',
        'options',
        'answers',
        'explain',
    ];

    protected $casts = [
        'options' => 'array',
        'answers' => 'array'
    ];

    /**
     * 分类
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function categories()
    {
        return $this->morphMany(ModelHasCategory::class, 'classified');
    }
}
