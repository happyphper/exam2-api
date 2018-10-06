<?php

namespace App\Observers;

use App\Models\Question;
use App\Models\TestQuestion;

class TestQuestionObserver
{
    /**
     * 监听题目与考试的关联
     *
     * @param TestQuestion $testQuestion
     */
    public function created(TestQuestion $testQuestion)
    {
        $test = $testQuestion->test;
        $test->questions_count++;
        $test->total_score += $testQuestion->score;
        $test->save();
    }

    /**
     * 监听题目与考试删除
     *
     * @param Question $category
     * @throws \Exception
     */
    public function deleting(TestQuestion $testQuestion)
    {
        $test = $testQuestion->test;
        $test->questions_count--;
        $test->total_score -= $testQuestion->score;
        $test->save();
    }
}
