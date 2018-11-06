<?php

namespace App\Observers;

use App\Models\Question;
use App\Models\QuestionResult;
use App\Models\ExamResult;

class QuestionResultObserver
{
    /**
     * 监听题目答案创建
     *
     * @param Question $category
     * @throws \Exception
     */
    public function created(QuestionResult $result)
    {
        $examResult = ExamResult::where('user_id', $result->user_id)->where('exam_id', $result->exam_id)->first();
        $examResult->finished_count++;
        $examResult->score += $result->score;
        $question = $result->question;
        if ($result->is_right) {
            $question->right_count += 1;
            $examResult->right_count += 1;
        } else {
            $question->wrong_count += 1;
            $examResult->wrong_count += 1;
        }
        $question->answered_count += 1;
        $question->accuracy = round($question->right_count/$question->answered_count) * 100;
        $question->save();
        if ($examResult->finished_count === $result->exam->questions_count) {
            $examResult->is_finished = true;
        }
        $examResult->save();
    }
}
