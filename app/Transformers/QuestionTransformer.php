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
    public function transform(Question $model)
    {
        return [
            'id' => $model->id,
            'title' => $model->title,
            'type' => $model->type,
            'options' => $model->options,
            'answers' => $model->answers,
            'explain' => $model->explain,
            'error_count' => $model->error_count,
            'correct_count' => $model->correct_count,
            'created_at' => $model->created_at ? $model->created_at->toDateTimeString() : null,
            'updated_at' => $model->updated_at ? $model->updated_at->toDateTimeString() : null,
        ];
    }
}