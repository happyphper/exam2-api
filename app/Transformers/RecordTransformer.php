<?php
/**
 * Created by PhpStorm.
 * User: wangbaolong
 * Date: 2018/8/19
 * Time: 14:52
 */

namespace App\Transformers;

use App\Models\Record;
use League\Fractal\TransformerAbstract;

class RecordTransformer extends TransformerAbstract
{
    public function transform(Record $model)
    {
        return [
            'id' => $model->id,
            'user_id' => $model->user_id,
            'test_id' => $model->test_id,
            'answer' => $model->answer,
            'error_count' => $model->error_count ?? 0,
            'correct_count' => $model->correct_count ?? 0,
            'grade' => $model->grade ?? 0,
            'created_at' => $model->created_at ? $model->created_at->toDateTimeString() : null,
            'updated_at' => $model->updated_at ? $model->updated_at->toDateTimeString() : null,
        ];
    }
}