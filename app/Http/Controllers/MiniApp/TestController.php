<?php

namespace App\Http\Controllers\MiniApp;

use App\Http\Requests\TestRequest;
use App\Models\Test;
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
        $me = auth()->user();

        $tests = $me->tests()->paginate(self::limit());

        return $this->response->paginator($tests, new TestTransformer());
    }

    /**
     * Display the specified resource.
     *
     * @param Test $test
     * @return \Illuminate\Http\Response
     */
    public function show($test)
    {
        $group = auth()->user()->group;

        $test = $group->tests()->findOrFail($test);

        return $this->response->item($test, new TestTransformer());
    }

    /**
     * 今日测试
     *
     * @param TestRequest $request
     * @param Test $test
     * @return \Dingo\Api\Http\Response
     */
    public function today()
    {
        $group = auth()->user()->group;

        $tests = $group->today()->get();

        return $this->response->collection($tests, new TestTransformer());
    }
}
