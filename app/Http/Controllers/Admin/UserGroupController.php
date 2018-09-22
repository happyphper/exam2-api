<?php

namespace App\Http\Controllers\Admin;

use App\Models\Group;
use App\Models\User;
use App\Http\Controllers\Controller;

class UserGroupController extends Controller
{
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
