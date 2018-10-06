<?php
/**
 * Created by PhpStorm.
 * User: wangbaolong
 * Date: 2018/8/19
 * Time: 14:52
 */

namespace App\Transformers;

use App\Models\GroupTest;
use League\Fractal\TransformerAbstract;

class GroupTestTransformer extends TransformerAbstract
{
    protected $defaultIncludes = ['group', 'test'];

    public function transform(GroupTest $model)
    {
        return [
            'id' => $model->id,
            'test_id' => $model->test_id,
            'group_id' => $model->group_id,
            'created_at' => $model->created_at ? $model->created_at->toDateTimeString() : null,
            'updated_at' => $model->updated_at ? $model->updated_at->toDateTimeString() : null,
        ];
    }

    public function includeGroup(GroupTest $model)
    {
        return $this->item($model->group, new GroupTransformer());
    }

    public function includeTest(GroupTest $model)
    {
        return $this->item($model->test, new TestTransformer());
    }
}