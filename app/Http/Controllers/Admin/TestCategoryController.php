<?php
/**
 * Created by PhpStorm.
 * User: wangbaolong
 * Date: 2018/8/19
 * Time: 18:21
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\TestCategoryRequest;
use App\Models\ModelHasCategory;
use App\Models\Test;

class TestCategoryController extends Controller
{
    /**
     * 为考试指定分类
     */
    public function store(Test $test, TestCategoryRequest $request)
    {
        foreach ($request->category_ids as $category_id) {
            if (!ModelHasCategory::where(['category_id' => $category_id, 'classified_id' => $test->id, 'classified_type' => Test::class])->count()) {
                ModelHasCategory::create([
                    'category_id' => $category_id,
                    'classified_id' => $test->id,
                    'classified_type' => Test::class
                ]);
            }
        }

        return $this->response->noContent();
    }

    public function destroy($test, $category)
    {
        $row = ModelHasCategory::where('category_id', $category)
            ->where('classified_id', $test->id)
            ->where('classified_type', Test::class)
            ->firstOrFail();

        $row->delete();

        return $this->response->noContent();
    }
}