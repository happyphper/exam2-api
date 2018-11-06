<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ClassroomExam extends Pivot
{
    protected $table = 'classroom_exams';

    public $incrementing = false;

    public $timestamps = false;

    public $fillable = ['exam_id',];

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }
}
