<?php
/**
 * Created by PhpStorm.
 * User: wangbaolong
 * Date: 2018/8/19
 * Time: 14:52
 */

namespace App\Transformers;

use App\Models\Classroom;
use League\Fractal\TransformerAbstract;

class ClassroomTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['user'];

    public function transform(Classroom $model)
    {
        return [
            'id' => $model->id,
            'title' => $model->title,
            'users_count' => $model->users_count ?? 0,
            'created_at' => $model->created_at ? $model->created_at->toDateTimeString() : null,
            'updated_at' => $model->updated_at ? $model->updated_at->toDateTimeString() : null,
        ];
    }

    public function includeUser(Classroom $model)
    {
        return $this->item($model->user, new UserTransformer());
    }
}