<?php
/**
 * Created by PhpStorm.
 * Course: wangbaolong
 * Date: 2018/8/19
 * Time: 14:52
 */

namespace App\Transformers;

use App\Models\Course;
use League\Fractal\TransformerAbstract;

class CourseTransformer extends TransformerAbstract
{
    protected $defaultIncludes = ['user'];

    public function transform(Course $model)
    {
        return [
            'id' => $model->id,
            'title' => $model->title,
            'questions_count' => $model->questions_count ?? 0,
            'created_at' => $model->created_at ? $model->created_at->toDateTimeString() : null,
            'updated_at' => $model->updated_at ? $model->updated_at->toDateTimeString() : null,
        ];
    }

    public function includeUser(Course $model)
    {
        return $this->item($model->user, new UserTransformer());
    }
}