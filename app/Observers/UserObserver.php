<?php

namespace App\Observers;

use App\Models\User;

class UserObserver
{
    public function created(User $user)
    {
        if ($user->group) {
            $user->group->increment('users_count');
        }
    }

    /**
     * 监听用户删除
     *
     * @param User $user
     */
    public function deleting(User $user)
    {
        $user->group->decrement('users_count');
    }
}
