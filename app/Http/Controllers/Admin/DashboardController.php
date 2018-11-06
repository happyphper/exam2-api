<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Classroom;
use App\Models\Question;
use App\Models\Test;

class DashboardController extends Controller
{
    /**
     * 控制台信息
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $meId = auth()->id();

        $courses = Course::where('user_id', $meId)->count();

        $questions = Question::where('user_id', $meId)->count();

        $classrooms = Classroom::where('user_id', $meId)->count();

        $tests = Test::where('user_id', $meId)->count();

        return $this->response->array([
            'courses_count' => $courses,
            'questions_count' => $questions,
            'classrooms_count' => $classrooms,
            'tests_count' => $tests
        ]);
    }
}
