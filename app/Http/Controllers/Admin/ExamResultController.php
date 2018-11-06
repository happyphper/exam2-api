<?php

namespace App\Http\Controllers\Admin;

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
        $data = ExamResult::orderBy('score', 'desc')->filtered()->paginate(self::limit());

        return $this->response->paginator($data, new ExamResultTransformer());
    }
}
