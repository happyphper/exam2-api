<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\TestQuestionRequest;
use App\Http\Requests\TestRequest;
use App\Models\Question;
use App\Models\Test;
use App\Models\TestQuestion;
use App\Transformers\QuestionTransformer;
use App\Http\Controllers\Controller;

class TestQuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Test $test)
    {
        return $this->response->collection($test->questions, new QuestionTransformer());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param TestRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(TestQuestionRequest $request, $test)
    {
        TestQuestion::create([
            'test_id' => $test,
            'question_id' => $request->question_id,
            'score' => $request->score
        ]);

        $question = Question::find($request->question_id);

        return $this->response->item($question, new QuestionTransformer())->setStatusCode(201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Test $test
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy($test, $question)
    {
        TestQuestion::where('test_id', $test)->where('question_id', $question)->delete();

        return $this->response->noContent();
    }
}
