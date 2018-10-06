<?php

namespace App\Http\Controllers\MiniApp;

use App\Models\GroupTest;
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
        $groupIds = auth()->user()->groups()->pluck('id')->toArray();

        $testIds = GroupTest::whereIn('group_id', $groupIds)->pluck('test_id');

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
        $me = auth()->user();

        $groupTest = GroupTest::whereIn('group_id', $me->groups()->pluck('id')->toArray())
            ->where('test_id', $test)
            ->firstOrFail();

        Test::today()->findOrFail($test);

        $result = TestResult::where('user_id', $me->id)->where('test_id', $groupTest->test_id)->first();
        if (!$result) {
            TestResult::create([
                'user_id' => $me->id,
                'test_id' => $groupTest->test_id,
                'questions_count' => $groupTest->test->questions_count,
                'total_score' => $groupTest->test->total_score,
                'group_id' => $groupTest->group_id
            ]);
        }

        $questions = $groupTest->test->questions()->select(['id', 'title', 'options', 'type'])->get();

        return $this->response->collection($questions, new QuestionTransformer());
    }
}
