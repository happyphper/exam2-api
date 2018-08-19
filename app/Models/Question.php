<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
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
