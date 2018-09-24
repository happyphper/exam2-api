<?php

namespace App\Observers;

use App\Models\Question;

class QuestionObserver
{
    /**
     * 监听题目创建
     *
     * @param Question $category
     * @throws \Exception
     */
    public function created(Question $question)
    {
        $question->course()->increment('questions_count');
    }

    /**
     * 监听题目删除
     *
     * @param Question $category
     * @throws \Exception
     */
    public function deleting(Question $question)
    {
        $question->course()->decrement('questions_count');
    }
}
