<?php

namespace App\Http\Controllers\MiniApp;

use App\Http\Requests\QuestionResultRequest;
use App\Models\Question;
use App\Models\QuestionResult;
use App\Models\Test;
use App\Models\TestQuestion;
use App\Transformers\QuestionResultTransformer;
use App\Http\Controllers\Controller;

class QuestionResultController extends Controller
{
    /**
     * 提交题目答案
     *
     * @param QuestionResultRequest $request
     * @param TestResultController $record
     * @return \Dingo\Api\Http\Response
     */
    public function store(QuestionResultRequest $request, Test $test)
    {
        // 携带正确答案返回
        $request->offsetSet('include', 'question');

        $result = QuestionResult::where('test_id', $test->id)
            ->where('user_id', auth()->id())
            ->where('test_id', $test->id)
            ->first();
        if ($result) {
            return $this->response->item($result, new QuestionResultTransformer())->setStatusCode(201);
        }

        // 正确与否
        $userAnswer = $request->input('answer');
        $userAnswer = collect($userAnswer)->map(function($item) {
            return (int)$item;
        })->toArray();
        sort($userAnswer);
        $score = TestQuestion::where('test_id', $test->id)
            ->where('question_id', $request->question_id)
            ->value('score');
        $answer = Question::where('id', $request->question_id)->value('answer');
        $isRight = $userAnswer === $answer;

        $result = new QuestionResult();
        $result->answer = $userAnswer;
        $result->question_id = $request->question_id;
        $result->user_id = auth()->id();
        $result->test_id = $test->id;
        $result->group_id = auth()->user()->group_id;
        $result->score = $isRight ? $score : 0;
        $result->is_right = $isRight;
        $result->save();

        return $this->response->item($result, new QuestionResultTransformer())->setStatusCode(201);
    }
}
