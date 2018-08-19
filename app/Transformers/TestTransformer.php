<?php
/**
 * Created by PhpStorm.
 * User: wangbaolong
 * Date: 2018/8/19
 * Time: 14:52
 */

namespace App\Transformers;

use App\Models\Test;
use League\Fractal\TransformerAbstract;

class TestTransformer extends TransformerAbstract
{
    public function transform(Test $model)
    {
        return [
            'id' => $model->id,
            'title' => $model->title,
            'type' => $model->type,
            'started_at' => $model->started_at ? $model->started_at->toDateTimeString() : null,
            'ended_at' => $model->ended_at ? $model->ended_at->toDateTimeString() : null,
            'created_at' => $model->created_at ? $model->created_at->toDateTimeString() : null,
            'updated_at' => $model->updated_at ? $model->updated_at->toDateTimeString() : null,
        ];
    }
}