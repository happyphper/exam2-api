<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Jedrzej\Searchable\SearchableTrait;
use Jedrzej\Sortable\SortableTrait;

class ExamResult extends Model
{
    use SearchableTrait,SortableTrait;
    public $searchable = [
        'exam_id',
        'classroom_id',
        'exam:title',
        'classroom:title',
        'user:name'
    ];
    public $sortable = ['*'];

    protected $guarded = ['id'];

    protected $casts = [
        'answer' => 'array'
    ];

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
