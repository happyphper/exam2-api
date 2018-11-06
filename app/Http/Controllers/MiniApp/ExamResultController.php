<?php

namespace App\Http\Controllers\MiniApp;

use App\Models\ExamResult;
use App\Transformers\ExamResultTransformer;
use App\Http\Controllers\Controller;

class ExamResultController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = ExamResult::where('user_id', auth()->id())->orderBy('created_at', 'desc')->paginate(self::limit());

        return $this->response->paginator($data, new ExamResultTransformer());
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($result)
    {
        $result = ExamResult::where('user_id', auth()->id())->find($result);

        return $this->response->item($result, new ExamResultTransformer());
    }
}
