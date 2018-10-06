<?php

namespace App\Http\Controllers\Admin;

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
    public function index($test, $group)
    {
        $records = TestResult::where('test_id', $test)->where('group_id', $group)->paginate(self::limit());

        return $this->response->paginator($records, new TestResultTransformer());
    }

    /**
     * Display the specified resource.
     *
     * @param TestResult $record
     * @return \Illuminate\Http\Response
     */
    public function show(TestResult $record)
    {
        return $this->response->item($record, new TestResultTransformer());
    }
}
