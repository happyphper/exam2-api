<?php
/**
 * Created by PhpStorm.
 * User: wangbaolong
 * Date: 2018/8/19
 * Time: 18:21
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ExamCategoryRequest;
use App\Models\ModelHasCategory;
use App\Models\Exam;

class ExamCategoryController extends Controller
{
    /**
     * 为考试指定分类
     */
    public function store(Exam $exam, ExamCategoryRequest $request)
    {
        foreach ($request->category_ids as $category_id) {
            if (!ModelHasCategory::where(['category_id' => $category_id, 'classified_id' => $exam->id, 'classified_type' => Exam::class])->count()) {
                ModelHasCategory::create([
                    'category_id' => $category_id,
                    'classified_id' => $exam->id,
                    'classified_type' => Exam::class
                ]);
            }
        }

        return $this->response->noContent();
    }

    public function destroy($exam, $category)
    {
        $row = ModelHasCategory::where('category_id', $category)
            ->where('classified_id', $exam->id)
            ->where('classified_type', Exam::class)
            ->firstOrFail();

        $row->delete();

        return $this->response->noContent();
    }
}