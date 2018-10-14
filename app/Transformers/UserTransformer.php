<?php
/**
 * Created by PhpStorm.
 * User: wangbaolong
 * Date: 2018/8/19
 * Time: 14:52
 */

namespace App\Transformers;

use App\Models\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['roles', 'group'];

    public function transform(User $model)
    {
        return [
            'id' => $model->id,
            'name' => $model->name,
            'email' => $model->email,
            'phone' => $model->phone,
            'group_id' => $model->group_id,
            'avatar' => $model->avatar ?? config('app.url') . '/images/avatar.png',
            'student_id' => $model->student_id,
            'created_at' => $model->created_at ? $model->created_at->toDateTimeString() : null,
            'updated_at' => $model->updated_at ? $model->updated_at->toDateTimeString() : null,
        ];
    }

    protected function includeGroup(User $model)
    {
        if (!$model->group) {
            return $this->null();
        }
        return $this->item($model->group, new GroupTransformer());
    }

    protected function includeRoles(User $model)
    {
        return $this->collection($model->roles, new RoleTransformer());
    }
}