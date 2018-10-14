<?php

namespace App\Observers;

use App\Models\Question;
use App\Models\QuestionResult;
use App\Models\TestResult;

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
        $testResult = TestResult::where('user_id', $result->user_id)->where('test_id', $result->test_id)->first();
        $testResult->finished_count++;
        $testResult->score += $result->score;
        if ($result->is_right) {
            $result->question->increment('right_count');
            $testResult->right_count++;
        } else {
            $result->question->increment('wrong_count');
            $testResult->wrong_count++;
        }
        if ($testResult->finished_count === $result->test->questions_count) {
            $testResult->is_finished = true;
        }
        $testResult->save();
    }
}
