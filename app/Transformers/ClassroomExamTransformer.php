<?php
/**
 * Created by PhpStorm.
 * User: wangbaolong
 * Date: 2018/8/19
 * Time: 14:52
 */

namespace App\Transformers;

use App\Models\ClassroomExam;
use League\Fractal\TransformerAbstract;

class ClassroomExamTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['classroom', 'exam'];

    public function transform(ClassroomExam $model)
    {
        return [
            'id' => $model->id,
            'exam_id' => $model->exam_id,
            'classroom_id' => $model->classroom_id,
            'created_at' => $model->created_at ? $model->created_at->toDateTimeString() : null,
            'updated_at' => $model->updated_at ? $model->updated_at->toDateTimeString() : null,
        ];
    }

    public function includeClassroom(ClassroomExam $model)
    {
        return $this->item($model->classroom, new ClassroomTransformer());
    }

    public function includeExam(ClassroomExam $model)
    {
        return $this->item($model->exam, new ExamTransformer());
    }
}