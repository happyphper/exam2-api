<?php

namespace App\Observers;

use App\Models\UserGroup;

class UserGroupObserver
{
    public function saved(UserGroup $userGroup)
    {
        $userGroup->group->increment('users_count');
    }

    public function deleting(UserGroup $userGroup)
    {
        $userGroup->group->decrement('users_count');
    }
}
