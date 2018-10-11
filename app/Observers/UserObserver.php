<?php

namespace App\Observers;

use App\Models\User;
use App\Models\UserGroup;

class UserObserver
{
    /**
     * 监听用户删除
     *
     * @param User $user
     */
    public function deleting(User $user)
    {
        // 移除用户群组
        $user->groups->each(function ($group) {
            $group->decrement('users_count');
        });
        UserGroup::where('user_id', $user->id)->delete();
    }
}
