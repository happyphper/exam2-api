<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CourseRequest;
use App\Models\Course;
use App\Transformers\CourseTransformer;
use App\Http\Controllers\Controller;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Course::own()->filtered()->orderByDesc('created_at')->paginate(request('per_page', 25));

        return $this->response->paginator($data, new CourseTransformer());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CourseRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(CourseRequest $request)
    {
        $course = new Course($request->all());
        $course->user_id = auth()->id();
        $course->save();

        return $this->response->item($course, new CourseTransformer())->setStatusCode(201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param CourseRequest $request
     * @param Course $course
     * @return \Illuminate\Http\Response
     */
    public function update(CourseRequest $request, Course $course)
    {
        $course->fill($request->all())->save();

        return $this->response->item($course, new CourseTransformer());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Course $course
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Course $course)
    {
        if ($course->tests()->exists() || $course->questions()->exists()) {
            $this->response->errorForbidden(__('Course has questions or tests,so you can not operate it.'));
        }
        $course->delete();

        return $this->response->noContent();
    }
}
