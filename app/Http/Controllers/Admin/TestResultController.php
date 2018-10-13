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
    public function index()
    {
        $data = TestResult::filtered()->paginate(self::limit());

        return $this->response->paginator($data, new TestResultTransformer());
    }
}
