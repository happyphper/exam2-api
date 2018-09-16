<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ModelHasCategory extends Pivot
{
    public $timestamps = false;

    /**
     * 多条关联对应的一条 Model 数据
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function classified()
    {
        return $this->morphTo();
    }

    /**
     * 获取该分类下的所有题目
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function questions()
    {
        return $this->morphedByMany(Question::class, 'classified');
    }

    /**
     * 获取该分类下的所有群组
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function groups()
    {
        return $this->morphedByMany(Group::class, 'classified');
    }
}
