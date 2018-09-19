<?php

namespace App\Observers;

use App\Models\User;

class UserObserver
{
    public function saved(User $user)
    {
        if ($user->group_id) {
            $user->group->increment('users_count');
        }
    }

    public function deleting(User $user)
    {
        if ($user->group_id) {
            $user->group->decrement('users_count');
        }
    }
}
