<?php

namespace App\Http\Controllers\Admin;

use App\Models\Course;
use App\Models\User;
use App\Transformers\CourseTransformer;
use App\Http\Controllers\Controller;

class UserCourseController extends Controller
{
    public function index()
    {
        $courses = Course::where('user_id', auth()->id())->get();

        return $this->response->collection($courses, new CourseTransformer());
    }
}
