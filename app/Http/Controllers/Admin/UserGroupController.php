<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\UserGroupRequest;
use App\Models\Group;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Transformers\GroupTransformer;
use Illuminate\Http\Request;

class UserGroupController extends Controller
{
    public function index(User $user)
    {
        $courses = $user->groups()->get();

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
            $user->password = $request->password ? bcrypt($request->password) : bcrypt(123456);
            $user->save();
            $user->groups()->attach($groupId);
            Group::find($groupId)->increment('users_count');
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
    public function store(Request $request, User $user)
    {
        $group = Group::findOrFail($request->group_id);

        $user->groups()->attach($request->group_id);
        $group->increment('users_count');

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
