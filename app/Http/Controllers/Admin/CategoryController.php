<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use App\Transformers\CategoryTransformer;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    public function __construct()
    {
        if (!in_array(request()->type, ['group', 'question'])) {
            $this->response->errorBadRequest('非法参数！');
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($type)
    {
        $categories = Category::where('type', $type)->paginate(self::limit());

        return $this->response->paginator($categories, new CategoryTransformer());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CategoryRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store($type, CategoryRequest $request, Category $category)
    {
        $category->type =$type;
        $category->fill($request->all());
        $category->save();

        return $this->response->item($category, new CategoryTransformer())->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     *
     * @param Category $category
     * @return \Illuminate\Http\Response
     */
    public function show($type, $category)
    {
        $category = Category::where('type', $type)->findOrFail($category);

        return $this->response->item($category, new CategoryTransformer());
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
        // TODO 已经被使用则无法删除，否则删除。

        $category = Category::where(compact('type'))->findOrFail($category);

        $category->delete();

        return $this->response->noContent();
    }
}
