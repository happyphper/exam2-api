<?php

namespace App\Observers;

use App\Models\QuestionResult;
use App\Models\TestResult;
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
        // 移除用户所有数据
        \DB::transaction(function () use($user){
            TestResult::where('user_id', $user->id)->delete();
            QuestionResult::where('user_id', $user->id)->delete();
            $user->group->decrement('users_count');
        });
    }
}
