<?php

namespace App\Http\Controllers\MiniApp;

use App\Models\QuestionResult;
use App\Models\ExamResult;
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

        $examsCount = ExamResult::where('user_id', $me->id)->count();

        $questionsCount = QuestionResult::where('user_id', $me->id)->count();

        return $this->response->array(['exams_count' => $examsCount, 'questions_count' => $questionsCount]);
    }
}
