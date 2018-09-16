<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use App\Transformers\CategoryTransformer;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    public $ids = [];

    public function __construct()
    {
        if (request() && !in_array(request()->type, ['group', 'question'])) {
            $this->response->errorBadRequest('非法参数！');
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($type, Category $category)
    {
        $data = $category->tree($type);

        return $this->response->array(compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CategoryRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store($type, CategoryRequest $request, Category $category)
    {
        $category->type = $type;
        $category->fill($request->all());
        $category->save();

        return $this->response->item($category, new CategoryTransformer())->setStatusCode(201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param CategoryRequest $request
     * @param Category $category
     * @return \Illuminate\Http\Response
     */
    public function update(CategoryRequest $request, $type, $category)
    {
        $category = Category::where(compact('type'))->findOrFail($category);

        $category->fill($request->only(['type', 'name']))->save();

        return $this->response->item($category, new CategoryTransformer());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Category $category
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy($type, $category)
    {
        $category = Category::where(compact('type'))->findOrFail($category);

        $category->delete();

        return $this->response->noContent();
    }
}
