<?php

namespace App\Http\Controllers\MiniApp;

use App\Enums\TestStatus;
use App\Models\ClassroomTest;
use App\Models\Test;
use App\Models\TestResult;
use App\Transformers\QuestionTransformer;
use App\Http\Controllers\Controller;
use App\Transformers\TestTransformer;

class TestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function today()
    {
        $me = auth()->user();

        $testIds = ClassroomTest::where('classroom_id', $me->classroom_id)->pluck('test_id');

        $tests = Test::today()->whereIn('id', $testIds)->get();

        return $this->response->collection($tests, new TestTransformer());
    }

    /**
     * Display the specified resource.
     *
     * @param Test $test
     * @return \Illuminate\Http\Response
     */
    public function start($test)
    {
        $test = Test::today()->findOrFail($test);
        if ($test->status !== TestStatus::Ongoing) {
            $this->response->errorForbidden(__('Test is not ongoing.'));
        }
        $me = auth()->user();
        $result = TestResult::where('user_id', $me->id)->where('test_id', $test->id)->first();
        if ($result && $result->is_finished) {
            $this->response->errorForbidden(__('Test is end.'));
        }

        $classroomTest = ClassroomTest::where('classroom_id', $me->classroom_id)
            ->where('test_id', $test->id)
            ->firstOrFail();
        if (!$result) {
            TestResult::create([
                'user_id' => $me->id,
                'test_id' => $classroomTest->test_id,
                'questions_count' => $classroomTest->test->questions_count,
                'total_score' => $classroomTest->test->total_score,
                'classroom_id' => $me->classroom_id,
                'course_id' => $test->course_id,
            ]);
        }

        $questions = $classroomTest->test->questions()->select(['id', 'title', 'options', 'type', 'accuracy', 'answered_count'])->get();

        $answeringCount = TestResult::where('test_id', $test->id)->count();

        return $this->response->collection($questions, new QuestionTransformer())->setMeta([
            'answering_count' => $answeringCount
        ]);
    }
}
