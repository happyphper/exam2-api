<?php
/**
 * Created by PhpStorm.
 * User: wangbaolong
 * Date: 2018/8/19
 * Time: 14:52
 */

namespace App\Transformers;

use App\Models\TestResult;
use League\Fractal\TransformerAbstract;

class TestResultTransformer extends TransformerAbstract
{
    public $availableIncludes = ['test', 'group'];

    public function transform(TestResult $model)
    {
        return [
            'id' => $model->id,
            'user_id' => $model->user_id,
            'test_id' => $model->test_id,
            'wrong_count' => $model->wrong_count ?? 0,
            'right_count' => $model->right_count ?? 0,
            'questions_count' => $model->questions_count ?? 0,
            'score' => $model->score ?? 0,
            'total_score' => $model->total_score ?? 0,
            'finished_count' => $model->finished_count ?? 0,
            'is_finished' => (bool)$model->is_finished,
            'created_at' => $model->created_at ? $model->created_at->toDateTimeString() : null,
            'updated_at' => $model->updated_at ? $model->updated_at->toDateTimeString() : null,
        ];
    }

    public function includeTest(TestResult $result)
    {
        return $this->item($result->test, new TestTransformer());
    }

    public function includeGroup(TestResult $result)
    {
        return $this->item($result->group, new GroupTransformer());
    }
}