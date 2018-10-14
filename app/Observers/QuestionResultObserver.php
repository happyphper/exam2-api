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
        $question = $result->question;
        if ($result->is_right) {
            $question->right_count += 1;
            $testResult->right_count += 1;
        } else {
            $question->wrong_count += 1;
            $testResult->wrong_count += 1;
        }
        $question->answered_count += 1;
        $question->accuracy = round($question->right_count/$question->answered_count) * 100;
        $question->save();
        if ($testResult->finished_count === $result->test->questions_count) {
            $testResult->is_finished = true;
        }
        $testResult->save();
    }
}
