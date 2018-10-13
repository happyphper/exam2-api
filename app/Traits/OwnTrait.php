<?php
/**
 * Created by PhpStorm.
 * User: wangbaolong
 * Date: 2018/10/13
 * Time: 16:26
 */

namespace App\Traits;

trait OwnTrait
{
    public function scopeOwn($query)
    {
        return $query->where('user_id', auth()->id());
    }
}