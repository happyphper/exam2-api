<?php

namespace App\Http\Controllers\MiniApp;

use App\Models\QuestionResult;
use App\Models\TestResult;
use App\Http\Controllers\Controller;

class StatController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {
        $me = auth()->user();

        $testsCount = TestResult::where('user_id', $me->id)->count();

        $questionsCount = QuestionResult::where('user_id', $me->id)->count();

        return $this->response->array(['tests_count' => $testsCount, 'questions_count' => $questionsCount]);
    }
}
