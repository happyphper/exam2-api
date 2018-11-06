<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ExamStatus;
use App\Http\Requests\ExamQuestionRequest;
use App\Http\Requests\ExamRequest;
use App\Models\Exam;
use App\Models\Question;
use App\Models\ExamQuestion;
use App\Transformers\QuestionTransformer;
use App\Http\Controllers\Controller;

class ExamQuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Exam $exam)
    {
        return $this->response->collection($exam->questions, new QuestionTransformer());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ExamRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(ExamQuestionRequest $request, Exam $exam)
    {
        if ($exam->status !== ExamStatus::Unstart) {
            $this->response->errorForbidden(__('Only unstart status allows delete or update.'));
        }

        ExamQuestion::create([
            'exam_id' => $exam->id,
            'question_id' => $request->question_id,
            'score' => $request->score
        ]);

        $question = Question::find($request->question_id);

        return $this->response->item($question, new QuestionTransformer())->setStatusCode(201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param ExamRequest $exam
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy($exam, $question)
    {
        if ($exam->status !== ExamStatus::Unstart) {
            $this->response->errorForbidden(__('Only unstart status allows delete or update.'));
        }

        ExamQuestion::where('exam_id', $exam)->where('question_id', $question)->delete();

        return $this->response->noContent();
    }
}
