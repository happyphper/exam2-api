<?php
/**
 * Created by PhpStorm.
 * User: wangbaolong
 * Date: 2018/8/19
 * Time: 14:52
 */

namespace App\Transformers;

use App\Enums\ExamStatus;
use App\Models\Exam;
use League\Fractal\TransformerAbstract;

class ExamTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['classrooms', 'result', 'course', 'categories'];

    public function transform(Exam $model)
    {
        return [
            'id' => $model->id,
            'title' => $model->title,
            'started_at' => $model->started_at ? $model->started_at->toDateTimeString() : null,
            'ended_at' => $model->ended_at ? $model->ended_at->toDateTimeString() : null,
            'status' => $model->status ?? ExamStatus::Unstart,
            'status_translate' => $model->status ? ExamStatus::getDescription($model->status) : ExamStatus::getDescription(ExamStatus::Unstart),
            'created_at' => $model->created_at ? $model->created_at->toDateTimeString() : null,
            'updated_at' => $model->updated_at ? $model->updated_at->toDateTimeString() : null,
        ];
    }

    public function includeClassrooms(Exam $model)
    {
        return $this->collection($model->classrooms, new ClassroomTransformer());
    }

    public function includeCategories(Exam $model)
    {
        return $this->collection($model->categories, new CategoryTransformer());
    }

    public function includeCourse(Exam $model)
    {
        return $this->item($model->course, new CourseTransformer());
    }

    public function includeResult(Exam $model)
    {
        if ($result = $model->result()->where('user_id', auth()->id())->first()) {
            return $this->item($result, new ExamResultTransformer());
        }
        return $this->null();
    }
}