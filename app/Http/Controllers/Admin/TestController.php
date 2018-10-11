<?php

namespace App\Http\Controllers\Admin;

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
        $tests = Test::filtered()->paginate(self::limit());

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
        $test->user_id = 1;
        $test->save();

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
        $test->fill($request->all())->save();

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
        $test->delete();

        return $this->response->noContent();
    }
}
