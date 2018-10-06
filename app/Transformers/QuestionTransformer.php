<?php
/**
 * Created by PhpStorm.
 * User: wangbaolong
 * Date: 2018/8/19
 * Time: 14:52
 */

namespace App\Transformers;

use App\Models\Question;
use League\Fractal\TransformerAbstract;

class QuestionTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['course'];

    public function transform(Question $model)
    {
        return [
            'id' => $model->id,
            'title' => $model->title,
            'type' => $model->type,
            'options' => $model->options,
            'answer' => $model->answer,
            'explain' => $model->explain,
            'wrong_count' => $model->wrong_count ?? 0,
            'right_count' => $model->right_count ?? 0,
            'score' => $model->pivot->score ?? null,
            'created_at' => $model->created_at ? $model->created_at->toDateTimeString() : null,
            'updated_at' => $model->updated_at ? $model->updated_at->toDateTimeString() : null,
        ];
    }

    public function includeUser(Question $model)
    {
        return $this->item($model->user, new UserTransformer());
    }

    public function includeCourse(Question $model)
    {
        return $this->item($model->course, new CourseTransformer());
    }
}