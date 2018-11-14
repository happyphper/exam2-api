<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ShareType;
use App\Http\Requests\ClassroomRequest;
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
        $data = Course::ofShare(auth()->id(), ShareType::Question)->filtered()->orderByDesc('created_at')->paginate(request('per_page', 25));

        return $this->response->paginator($data, new CourseTransformer());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ClassroomRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(ClassroomRequest $request)
    {
        $course = new Course($request->all());
        $course->user_id = auth()->id();
        $course->save();

        return $this->response->item($course, new CourseTransformer())->setStatusCode(201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ClassroomRequest $request
     * @param Course           $course
     * @return \Illuminate\Http\Response
     */
    public function update(ClassroomRequest $request, Course $course)
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
        if ($course->exams()->exists() || $course->questions()->exists()) {
            $this->response->errorForbidden(__('Course has questions or exams,so you can not operate it.'));
        }
        $course->delete();

        return $this->response->noContent();
    }
}
