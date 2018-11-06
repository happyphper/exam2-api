<?php

namespace App\Models;

use App\Traits\OwnTrait;
use Illuminate\Database\Eloquent\Model;
use Jedrzej\Searchable\SearchableTrait;
use Jedrzej\Sortable\SortableTrait;

class Course extends Model
{
    use OwnTrait;

    use SearchableTrait,SortableTrait;

    public $searchable = ['title', 'user:name'];

    public $sortable = ['*'];

    protected $fillable = ['title'];

    /**
     * 测验
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 题目
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    /**
     * 考试
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function exams()
    {
        return $this->belongsTo(Exam::class);
    }
}
