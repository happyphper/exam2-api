<?php
/**
 * Created by PhpStorm.
 * User: wangbaolong
 * Date: 2018/8/19
 * Time: 18:21
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClassroomTestRequest;
use App\Models\Classroom;
use App\Models\ClassroomTest;
use App\Models\TestResult;
use App\Transformers\ClassroomTestTransformer;
use App\Transformers\TestTransformer;

class ClassroomTestController extends Controller
{
    /**
     *
     *
     * @param ClassroomTestRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function index(Classroom $classroom)
    {
        $data = $classroom->tests()->paginate(self::limit());

        return $this->response->paginator($data, new TestTransformer());
    }

    /**
     *
     *
     * @param ClassroomTestRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function store(ClassroomTestRequest $request, Classroom $classroom)
    {
        $classroomTest = new ClassroomTest($request->all());
        $classroomTest->classroom_id = $classroom->id;
        $classroomTest->save();

        return $this->response->item($classroomTest, new ClassroomTestTransformer())->setStatusCode(201);
    }

    /**
     *
     *
     * @param ClassroomTestRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function update(ClassroomTestRequest $request, Classroom $classroom, ClassroomTest $test)
    {
        $test->fill($request->all());
        $test->save();

        return $this->response->item($test, new ClassroomTestTransformer())->setStatusCode(201);
    }

    /**
     *
     *
     * @param ClassroomTestRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function destroy(Classroom $classroom, ClassroomTest $classroomTest)
    {
        $userIds = $classroom->users()->pluck('id');

        if (TestResult::where('test_id', $classroomTest->test_id)->whereIn('user_id', $userIds->toArray())->exists()) {
            $this->response->errorForbidden(__('Users joined the test, so you can not operate it.'));
        }

        $classroomTest->delete();

        return $this->response->noContent();
    }
}