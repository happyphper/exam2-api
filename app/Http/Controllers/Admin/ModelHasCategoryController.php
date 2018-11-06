<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use App\Models\Classroom;
use App\Models\ModelHasCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ModelHasCategoryController extends Controller
{
    /**
     * 为 Model 添加分类
     *
     * @param $type
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function store($type, Request $request)
    {
        // TODO 添加验证
        if ($type === 'classroom') {
            $model = Classroom::findOrFail($request->classified_id);
        } else {
            $model = Classroom::findOrFail($request->classified_id);
        }

        ModelHasCategory::create([
            'category_id' => $request->category_id,
            'classified_id' => $model->id,
            'classified_type' => get_class($model)
        ]);;

        return $this->response->array(null)->setStatusCode(201);
    }

    /**
     * 为 Model 移除分类
     *
     * @param $type
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function destroy(Request $request, $type, Category $category)
    {
        // TODO 添加验证
        if ($type === 'classroom') {
            $model = Classroom::findOrFail($request->classified_id);
        } else {
            $model = Classroom::findOrFail($request->classified_id);
        }

        ModelHasCategory::where('classified_id', $model->id)
            ->where('classified_type', get_class($model))
            ->where('category_id', $category->id)
            ->delete();

        return $this->response->noContent();
    }
}
