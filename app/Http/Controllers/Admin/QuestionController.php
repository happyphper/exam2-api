<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\QuestionRequest;
use App\Models\Course;
use App\Models\Question;
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
        $questions = Question::ofShare(auth()->id())->filtered()->orderByDesc('created_at')->paginate(self::limit());

        return $this->response->paginator($questions, new QuestionTransformer());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     * @throws \Throwable
     */
    public function bulk(Request $request)
    {
        $course = Course::findOrFail($request->course_id);

        $questions = $request->questions;

        $questions = collect($questions)->map(function ($question, $index) use ($questions) {
            if (in_array($question['title'], $questions)) {
                $this->response->errorBadRequest("第 {$index} 道题目在 Excel 中重复出现，请处理。");
            }
            if (Question::where('title', $question['title'])->exists()) {
                $this->response->errorBadRequest('『' . $question['title'] . '』题目已存在于数据库之中，请移除后再导入。');
            }
            return [
                'title' => $question['title'],
                'type' => $question['type'],
                'options' => [
                    ['id' => 1, 'content' => $question['option1'], 'type' => 'text', 'status' => 0],
                    ['id' => 2, 'content' => $question['option2'], 'type' => 'text', 'status' => 0],
                    ['id' => 3, 'content' => $question['option3'], 'type' => 'text', 'status' => 0],
                    ['id' => 4, 'content' => $question['option4'], 'type' => 'text', 'status' => 0],
                ],
                'answer' => $question['answer'],
                'explain' => $question['explain'] ?? null,
            ];
        });

        \DB::transaction(function ()  use( $questions, $course){
            $questions->each(function ($question) use($course) {
                $item = new Question($question);
                $item->user_id = auth()->id();
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
        $question = new Question($request->all());
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
     * @param Question $question
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
        // TODO 当未完成测试包含该题目的测试时，则无法删除，否则删除

        $question->delete();

        return $this->response->noContent();
    }
}
