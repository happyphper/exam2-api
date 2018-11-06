<?php
/**
 * Created by PhpStorm.
 * User: wangbaolong
 * Date: 2018/10/13
 * Time: 16:26
 */

namespace App\Traits;

use App\Models\Share;

trait ShareTrait
{
    public function scopeOfShare($query, $userId, $type)
    {
        $userIds = Share::where('user_id', $userId)
            ->where('type', $type)
            ->pluck('share_user_id')->toArray();
        $userIds[] = $userId;

        return $query->whereIn('user_id', $userIds);
    }
}