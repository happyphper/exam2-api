<?php

namespace App\Http\Controllers\MiniApp;

use App\Enums\ExamStatus;
use App\Http\Requests\QuestionResultRequest;
use App\Models\Question;
use App\Models\QuestionResult;
use App\Models\Exam;
use App\Models\ExamQuestion;
use App\Models\ExamResult;
use App\Transformers\QuestionResultTransformer;
use App\Http\Controllers\Controller;

class QuestionResultController extends Controller
{
    /**
     * 提交题目答案
     *
     * @param QuestionResultRequest $request
     * @param ExamResultController  $record
     * @return \Dingo\Api\Http\Response
     */
    public function store(QuestionResultRequest $request, Exam $exam)
    {
        $user = auth()->user();
        if ($exam->status !== ExamStatus::Ongoing) {
            $this->response->errorForbidden(__('Exam is not ongoing.'));
        }
        $examResult = ExamResult::where('exam_id', $exam->id)->where('user_id', $user->id)->first();
        if ($examResult->is_finished) {
            $this->response->errorForbidden(__('Exam is end.'));
        }

        // 携带正确答案返回
        $request->offsetSet('include', 'question');

        $result = QuestionResult::where('exam_id', $exam->id)
            ->where('user_id', auth()->id())
            ->where('exam_id', $exam->id)
            ->where('question_id', $request->question_id)
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
        $score = ExamQuestion::where('exam_id', $exam->id)
            ->where('question_id', $request->question_id)
            ->value('score');
        $answer = Question::where('id', $request->question_id)->value('answer');
        $isRight = $userAnswer === $answer;

        $result = new QuestionResult();
        $result->answer = $userAnswer;
        $result->question_id = $request->question_id;
        $result->user_id = $user->id;
        $result->exam_id = $exam->id;
        $result->classroom_id = $user->classroom_id;
        $result->score = $isRight ? $score : 0;
        $result->is_right = $isRight;
        $result->save();

        return $this->response->item($result, new QuestionResultTransformer())->setStatusCode(201);
    }
}
