<?php

namespace App\Http\Controllers\MiniApp;

use App\Models\TestResult;
use App\Transformers\TestResultTransformer;
use App\Http\Controllers\Controller;

class TestResultController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($test)
    {
        $data = TestResult::where('user_id', auth()->id())->where('test_id', $test)->paginate(self::limit());

        return $this->response->paginator($data, new TestResultTransformer());
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($test, $result)
    {
        $result = TestResult::where('user_id', auth()->id())->where('test_id', $test)->find($result);

        return $this->response->item($result, new TestResultTransformer());
    }
}
