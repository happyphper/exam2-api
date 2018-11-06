<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ShareType;
use App\Http\Requests\ClassroomRequest;
use App\Models\Classroom;
use App\Http\Controllers\Controller;
use App\Transformers\ClassroomTransformer;

class ClassroomController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $classrooms = Classroom::ofShare(auth()->id(), ShareType::Classroom)->filtered()->paginate(self::limit());

        return $this->response->paginator($classrooms, new ClassroomTransformer());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ClassroomRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(ClassroomRequest $request, Classroom $classroom)
    {
        $classroom->fill($request->all());
        $classroom->user_id = auth()->id();
        $classroom->save();

        return $this->response->item($classroom, new ClassroomTransformer())->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     *
     * @param Classroom $classroom
     * @return \Illuminate\Http\Response
     */
    public function show(Classroom $classroom)
    {
        return $this->response->item($classroom, new ClassroomTransformer());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ClassroomRequest $request
     * @param Classroom        $classroom
     * @return \Illuminate\Http\Response
     */
    public function update(ClassroomRequest $request, Classroom $classroom)
    {
        $classroom->fill($request->all())->save();

        return $this->response->item($classroom, new ClassroomTransformer());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Classroom $classroom
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Classroom $classroom)
    {
        if ($classroom->users()->exists() || $classroom->exams()->exists()) {
            $this->response->errorForbidden(__('Exam has users or exams,so you can not operate it.'));
        }

        $classroom->delete();

        return $this->response->noContent();
    }
}
