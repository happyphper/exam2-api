<?php
/**
 * Created by PhpStorm.
 * User: wangbaolong
 * Date: 2018/8/19
 * Time: 14:52
 */

namespace App\Transformers;

use App\Enums\TestStatus;
use App\Models\Test;
use League\Fractal\TransformerAbstract;

class TestTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['classrooms', 'result', 'course', 'categories'];

    public function transform(Test $model)
    {
        return [
            'id' => $model->id,
            'title' => $model->title,
            'started_at' => $model->started_at ? $model->started_at->toDateTimeString() : null,
            'ended_at' => $model->ended_at ? $model->ended_at->toDateTimeString() : null,
            'status' => $model->status ?? TestStatus::Unstart,
            'status_translate' => $model->status ? TestStatus::getDescription($model->status) : TestStatus::getDescription(TestStatus::Unstart),
            'created_at' => $model->created_at ? $model->created_at->toDateTimeString() : null,
            'updated_at' => $model->updated_at ? $model->updated_at->toDateTimeString() : null,
        ];
    }

    public function includeClassrooms(Test $model)
    {
        return $this->collection($model->classrooms, new ClassroomTransformer());
    }

    public function includeCategories(Test $model)
    {
        return $this->collection($model->categories, new CategoryTransformer());
    }

    public function includeCourse(Test $model)
    {
        return $this->item($model->course, new CourseTransformer());
    }

    public function includeResult(Test $model)
    {
        if ($result = $model->result()->where('user_id', auth()->id())->first()) {
            return $this->item($result, new TestResultTransformer());
        }
        return $this->null();
    }
}