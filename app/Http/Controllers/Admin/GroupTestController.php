<?php
/**
 * Created by PhpStorm.
 * User: wangbaolong
 * Date: 2018/8/19
 * Time: 18:21
 */

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Http\Requests\GroupTestRequest;
use App\Models\GroupTest;
use App\Models\Test;
use App\Transformers\TestTransformer;

class GroupTestController extends Controller
{
    /**
     * 为测试指定群组
     */
    public function store(GroupTestRequest $request)
    {
        GroupTest::create($request->all());

        $test = Test::find($request->test_id);

        return $this->response->item($test, new TestTransformer())->setStatusCode(201);
    }
}