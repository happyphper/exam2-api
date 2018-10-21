<?php
/**
 * Created by PhpStorm.
 * User: wangbaolong
 * Date: 2018/8/19
 * Time: 18:21
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\GroupTestRequest;
use App\Models\Group;
use App\Models\GroupTest;
use App\Models\TestResult;
use App\Transformers\GroupTestTransformer;
use App\Transformers\TestTransformer;

class GroupTestController extends Controller
{
    /**
     *
     *
     * @param GroupTestRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function index(Group $group)
    {
        $data = $group->tests()->paginate(self::limit());

        return $this->response->paginator($data, new TestTransformer());
    }

    /**
     *
     *
     * @param GroupTestRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function store(GroupTestRequest $request, Group $group)
    {
        $groupTest = new GroupTest($request->all());
        $groupTest->group_id = $group->id;
        $groupTest->save();

        return $this->response->item($groupTest, new GroupTestTransformer())->setStatusCode(201);
    }

    /**
     *
     *
     * @param GroupTestRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function update(GroupTestRequest $request, Group $group, GroupTest $test)
    {
        $test->fill($request->all());
        $test->save();

        return $this->response->item($test, new GroupTestTransformer())->setStatusCode(201);
    }

    /**
     *
     *
     * @param GroupTestRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function destroy(Group $group, GroupTest $groupTest)
    {
        $userIds = $group->users()->pluck('id');

        if (TestResult::where('test_id', $groupTest->test_id)->whereIn('user_id', $userIds->toArray())->exists()) {
            $this->response->errorForbidden(__('Users joined the test, so you can not operate it.'));
        }

        $groupTest->delete();

        return $this->response->noContent();
    }
}