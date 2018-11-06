<?php
/**
 * Created by PhpStorm.
 * User: wangbaolong
 * Date: 2018/8/19
 * Time: 18:21
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClassroomExamRequest;
use App\Models\Classroom;
use App\Models\ClassroomExam;
use App\Models\ExamResult;
use App\Transformers\ClassroomExamTransformer;
use App\Transformers\ExamTransformer;

class ClassroomExamController extends Controller
{
    /**
     *
     *
     * @param ClassroomExamRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function index(Classroom $classroom)
    {
        $data = $classroom->exams()->paginate(self::limit());

        return $this->response->paginator($data, new ExamTransformer());
    }

    /**
     *
     *
     * @param ClassroomExamRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function store(ClassroomExamRequest $request, Classroom $classroom)
    {
        $classroomExam = new ClassroomExam($request->all());
        $classroomExam->classroom_id = $classroom->id;
        $classroomExam->save();

        return $this->response->item($classroomExam, new ClassroomExamTransformer())->setStatusCode(201);
    }

    /**
     *
     *
     * @param ClassroomExamRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function update(ClassroomExamRequest $request, Classroom $classroom, ClassroomExam $exam)
    {
        $exam->fill($request->all());
        $exam->save();

        return $this->response->item($exam, new ClassroomExamTransformer())->setStatusCode(201);
    }

    /**
     *
     *
     * @param ClassroomExamRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function destroy(Classroom $classroom, ClassroomExam $classroomExam)
    {
        $userIds = $classroom->users()->pluck('id');

        if (ExamResult::where('exam_id', $classroomExam->exam_id)->whereIn('user_id', $userIds->toArray())->exists()) {
            $this->response->errorForbidden(__('Users joined the exam, so you can not operate it.'));
        }

        $classroomExam->delete();

        return $this->response->noContent();
    }
}