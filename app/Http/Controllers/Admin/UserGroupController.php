<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\UserGroupRequest;
use App\Models\Group;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Transformers\GroupTransformer;

class UserGroupController extends Controller
{
    public function index()
    {
        $courses = Group::where('user_id', auth()->id())->get();

        return $this->response->collection($courses, new GroupTransformer());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param User $user
     * @param Group $group
     * @return \Dingo\Api\Http\Response
     */
    public function bulk(UserGroupRequest $request)
    {
        $groupId = $request->group_id;

        $users = $request->users;

        foreach ($users as $userData) {
            $user = new User();
            $user->fill($userData);
            $user->password = bcrypt(123456);
            $user->save();
            $user->groups()->attach($groupId);
        }

        return $this->response->noContent();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param User $user
     * @param Group $group
     * @return \Dingo\Api\Http\Response
     */
    public function store(User $user, Group $group)
    {
        $user->groups()->attach($group->id);

        return $this->response->noContent();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user
     * @return \Dingo\Api\Http\Response
     */
    public function destroy(User $user, Group $group)
    {
        $user->groups()->detach($group->id);

        return $this->response->noContent();
    }
}
