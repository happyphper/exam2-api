<?php

namespace App\Observers;

use App\Models\Question;
use App\Models\ExamQuestion;

class ExamQuestionObserver
{
    /**
     * 监听题目与考试的关联
     *
     * @param ExamQuestion $examQuestion
     */
    public function created(ExamQuestion $examQuestion)
    {
        $exam = $examQuestion->exam;
        $exam->questions_count++;
        $exam->total_score += $examQuestion->score;
        $exam->save();
    }

    /**
     * 监听题目与考试删除
     *
     * @param Question $category
     * @throws \Exception
     */
    public function deleting(ExamQuestion $examQuestion)
    {
        $exam = $examQuestion->exam;
        $exam->questions_count--;
        $exam->total_score -= $examQuestion->score;
        $exam->save();
    }
}
