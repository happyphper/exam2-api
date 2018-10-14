<?php
/**
 * Created by PhpStorm.
 * Group: wangbaolong
 * Date: 2018/8/19
 * Time: 14:52
 */

namespace App\Transformers;

use App\Models\Group;
use League\Fractal\TransformerAbstract;

class GroupTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['user'];

    public function transform(Group $model)
    {
        return [
            'id' => $model->id,
            'name' => $model->name,
            'users_count' => $model->users_count ?? 0,
            'created_at' => $model->created_at ? $model->created_at->toDateTimeString() : null,
            'updated_at' => $model->updated_at ? $model->updated_at->toDateTimeString() : null,
        ];
    }

    public function includeUser(Group $model)
    {
        return $this->item($model->user, new UserTransformer());
    }
}