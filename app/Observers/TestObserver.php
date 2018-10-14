<?php

namespace App\Observers;

use App\Models\Question;
use App\Models\Test;

class TestObserver
{
    /**
     * 监听题目与考试的关联
     *
     * @param Test $test
     */
    public function saved(Test $test)
    {
        $test->course->increment('tests_count');
    }

    /**
     * 监听题目与考试删除
     *
     * @param Question $category
     * @throws \Exception
     */
    public function deleting(Test $test)
    {
        $test->course->decrement('tests_count');
    }
}
