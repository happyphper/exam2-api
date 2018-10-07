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
    public function index()
    {
        $data = TestResult::where('user_id', auth()->id())->orderBy('created_at', 'desc')->paginate(self::limit());

        return $this->response->paginator($data, new TestResultTransformer());
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($result)
    {
        $result = TestResult::where('user_id', auth()->id())->find($result);

        return $this->response->item($result, new TestResultTransformer());
    }
}
