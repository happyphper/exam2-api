<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ShareType;
use App\Http\Requests\BulkQuestionRequest;
use App\Http\Requests\QuestionRequest;
use App\Models\Course;
use App\Models\Question;
use App\Models\QuestionResult;
use App\Transformers\QuestionTransformer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $questions = Question::ofShare(auth()->id(), ShareType::Question)->filtered()->sorted()->orderByDesc('created_at')->paginate(self::limit());

        return $this->response->paginator($questions, new QuestionTransformer());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     * @throws \Throwable
     */
    public function bulk(BulkQuestionRequest $request)
    {
        $course = Course::find($request->course_id);

        $questions = $request->questions;

        $questions = collect($questions)->map(function ($question, $index) use ($request, $questions) {
            $options = [];
            for ($i = 1; $i <= 10; $i++) {
                isset($questions['option' . $i]) && array_push($options, [
                    'id'      => $i,
                    'content' => $question['option' . $i],
                    'type'    => 'text',
                    'status'  => 0,
                ]);
            }
            return [
                'title'   => $question['title'],
                'type'    => $question['type'],
                'chapter' => $question['chapter'] ?? 0,
                'section' => $question['section'] ?? 0,
                'options' => $options,
                'answer'  => $question['answer'],
                'explain' => $question['explain'] ?? null,
            ];
        });

        \DB::transaction(function () use ($questions, $course) {
            $questions->each(function ($question) use ($course) {
                $item            = new Question($question);
                $item->user_id   = auth()->id();
                $item->course_id = $course->id;
                $item->save();
            });
        });

        return $this->response->created();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param QuestionRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(QuestionRequest $request)
    {
        $question          = new Question($request->all());
        $question->user_id = auth()->id();
        $question->save();

        return $this->response->item($question, new QuestionTransformer())->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     *
     * @param Question $question
     * @return \Illuminate\Http\Response
     */
    public function show(Question $question)
    {
        return $this->response->item($question, new QuestionTransformer());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param QuestionRequest $request
     * @param Question        $question
     * @return \Illuminate\Http\Response
     */
    public function update(QuestionRequest $request, Question $question)
    {
        $question->fill($request->all())->save();

        return $this->response->item($question, new QuestionTransformer());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Question $question
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Question $question)
    {
        if (QuestionResult::where('question_id', $question->id)->exists()) {
            $this->response->errorForbidden(__('Users answered the question, so you can not operate it.'));
        }

        $question->delete();

        return $this->response->noContent();
    }
}
