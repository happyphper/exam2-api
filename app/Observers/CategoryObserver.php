<?php

namespace App\Observers;

use App\Models\Category;
use App\Models\ModelHasCategory;
use DB;

class CategoryObserver
{
    /**
     * 被批量删除的分类 ID 数组
     *
     * @var array
     */
    private $ids = [];

    /**
     * 删除之前执行
     *
     * @param Category $category
     * @throws \Exception
     */
    public function deleting(Category $category)
    {
        // 查询其下所有的子分类
        $data = $category->getChildren($category);
        // 获取所有 ID
        $this->pluckMultipleArray($data);
        // 将自身 ID 加入，因为要清除当前关联的 ModelHasCategory 数据
        array_push($this->ids, $category->id);
        DB::transaction(function () use ($category, $data) {
            // 清除子分类数据
            Category::whereIn('id', $this->ids)->delete();
            // 清除相关数据
            ModelHasCategory::whereIn('category_id', $this->ids)->delete();
        });
    }

    /**
     * 获取多维数组的指定字段
     *
     * @param $arr
     * @param string $column
     * @param string $field
     * @return void
     */
    private function pluckMultipleArray($arr, $column = 'id', $field = 'children'): void
    {
        foreach ($arr as $index => $item) {
            array_push($this->ids, $item[$column]);
            if (isset($item[$field]) && count($item[$field])) {
                $this->pluckMultipleArray($item[$field]);
            }
        }
    }
}
