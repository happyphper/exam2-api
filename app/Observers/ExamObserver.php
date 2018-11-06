<?php

namespace App\Observers;

use App\Models\Question;
use App\Models\Exam;

class ExamObserver
{
    /**
     * 监听题目与考试的关联
     *
     * @param Exam $exam
     */
    public function saved(Exam $exam)
    {
        $exam->course->increment('exams_count');
    }

    /**
     * 监听题目与考试删除
     *
     * @param Question $category
     * @throws \Exception
     */
    public function deleting(Exam $exam)
    {
        $exam->course->decrement('exams_count');
    }
}
