<?php
/**
 * Created by PhpStorm.
 * User: wangbaolong
 * Date: 2018/8/19
 * Time: 14:52
 */

namespace App\Transformers;

use App\Models\ClassroomTest;
use League\Fractal\TransformerAbstract;

class ClassroomTestTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['classroom', 'test'];

    public function transform(ClassroomTest $model)
    {
        return [
            'id' => $model->id,
            'test_id' => $model->test_id,
            'classroom_id' => $model->classroom_id,
            'created_at' => $model->created_at ? $model->created_at->toDateTimeString() : null,
            'updated_at' => $model->updated_at ? $model->updated_at->toDateTimeString() : null,
        ];
    }

    public function includeClassroom(ClassroomTest $model)
    {
        return $this->item($model->classroom, new ClassroomTransformer());
    }

    public function includeTest(ClassroomTest $model)
    {
        return $this->item($model->test, new TestTransformer());
    }
}