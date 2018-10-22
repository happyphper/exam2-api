<?php

namespace App\Http\Controllers\Admin;

use App\Enums\TestStatus;
use App\Http\Requests\TestRequest;
use App\Models\Test;
use App\Models\TestResult;
use App\Models\User;
use App\Transformers\TestTransformer;
use App\Http\Controllers\Controller;

class TestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tests = Test::own()->filtered()->paginate(self::limit());

        return $this->response->paginator($tests, new TestTransformer());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param TestRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(TestRequest $request)
    {
        $test = new Test($request->all());
        $test->user_id = auth()->id();
        $test->save();
        $test->groups()->attach($request->group_ids);

        return $this->response->item($test, new TestTransformer())->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     *
     * @param Test $test
     * @return \Illuminate\Http\Response
     */
    public function show(Test $test)
    {
        return $this->response->item($test, new TestTransformer());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param TestRequest $request
     * @param Test $test
     * @return \Illuminate\Http\Response
     */
    public function update(TestRequest $request, Test $test)
    {
        if ($test->status !== TestStatus::Unstart) {
            $this->response->errorForbidden(__('Only unstart status allows delete or update.'));
        }

        $test->fill($request->all())->save();

        $test->groups()->sync($request->group_ids);

        return $this->response->item($test, new TestTransformer());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Test $test
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Test $test)
    {
        if ($test->status !== TestStatus::Unstart) {
            $this->response->errorForbidden(__('Only unstart status allows delete or update.'));
        }

        $test->delete();

        return $this->response->noContent();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param TestRequest $request
     * @param Test $test
     * @return \Illuminate\Http\Response
     */
    public function end(Test $test)
    {
        $course = $test->course()->first();

        // 将所有未答题学员进行数据添加
        $users = User::whereIn('group_id', request('group_ids'))->get(['id', 'group_id']);
        \DB::transaction(function () use ($users, $test, $course) {
            $results = TestResult::whereIn('user_id', $users->pluck('id')->toArray())->where('test_id', $test->id)->get();
            foreach ($users as $user) {
                $result = $results->where('user_id', $user->id)->first();
                if ($result && !$result->is_finished) {
                    $result->is_finished = true;
                    $result->save();
                } else {
                    TestResult::create([
                        'group_id' => $user->group_id,
                        'course_id' => $course->id,
                        'user_id' => $user->id,
                        'test_id' => $test->id,
                        'is_finished' => true,
                        'is_participated' => false
                    ]);
                }
            }
        });

        return $this->response->noContent();
    }
}
