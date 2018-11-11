<?php
/**
 * Created by PhpStorm.
 * User: wangbaolong
 * Date: 2018/8/19
 * Time: 14:52
 */

namespace App\Transformers;

use App\Models\Question;
use App\Models\QuestionResult;
use League\Fractal\TransformerAbstract;

class QuestionTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['course', 'result'];

    public function transform(Question $model)
    {
        return [
            'id'             => $model->id,
            'title'          => $model->title,
            'image'          => $model->image,
            'type'           => $model->type,
            'chapter'        => $model->chapter,
            'section'        => $model->section,
            'options'        => $model->options,
            'answer'         => $model->answer,
            'explain'        => $model->explain,
            'wrong_count'    => $model->wrong_count ?? 0,
            'right_count'    => $model->right_count ?? 0,
            'answered_count' => $model->answered_count ?? 0,
            'accuracy'       => $model->accuracy ?? 0,
            'score'          => $model->pivot->score ?? null,
            'course_id'      => $model->course_id,
            'created_at'     => $model->created_at ? $model->created_at->toDateTimeString() : null,
            'updated_at'     => $model->updated_at ? $model->updated_at->toDateTimeString() : null,
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

    public function includeResult(Question $model)
    {
        $item = QuestionResult::where('user_id', auth()->id())
            ->where('exam_id', request()->exam)
            ->where('question_id', $model->id)
            ->first();
        if (!$item) {
            return $this->null();
        }
        return $this->item($item, new QuestionResultTransformer());
    }
}