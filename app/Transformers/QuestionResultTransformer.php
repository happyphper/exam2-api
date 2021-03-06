<?php
/**
 * Created by PhpStorm.
 * User: wangbaolong
 * Date: 2018/8/19
 * Time: 14:52
 */

namespace App\Transformers;

use App\Models\QuestionResult;
use League\Fractal\TransformerAbstract;

class QuestionResultTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['question'];

    public function transform(QuestionResult $model)
    {
        return [
            'id' => $model->id,
            'user_id' => $model->user_id,
            'exam_id' => $model->exam_id,
            'question_id' => $model->question_id,
            'is_right' => $model->is_right,
            'score' => $model->score ?? 0,
            'created_at' => $model->created_at ? $model->created_at->toDateTimeString() : null,
            'updated_at' => $model->updated_at ? $model->updated_at->toDateTimeString() : null,
        ];
    }

    public function includeQuestion(QuestionResult $model)
    {
        return $this->item($model->question, new QuestionTransformer());
    }
}