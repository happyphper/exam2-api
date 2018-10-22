<?php
/**
 * Created by PhpStorm.
 * User: wangbaolong
 * Date: 2018/10/13
 * Time: 16:26
 */

namespace App\Traits;

use App\Models\ShareUser;

trait ShareTrait
{
    public function scopeOfShare($query, $userId)
    {
        $userIds = ShareUser::where('user_id', $userId)->pluck('share_user_id')->toArray();
        $userIds[] = $userId;

        return $query->whereIn('user_id', $userIds);
    }
}