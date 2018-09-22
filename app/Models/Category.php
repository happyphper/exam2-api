<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Jedrzej\Searchable\SearchableTrait;
use Jedrzej\Sortable\SortableTrait;

class Category extends Model
{
    use SearchableTrait,SortableTrait;
    public $searchable = ['*'];
    public $sortable = ['*'];

    protected $fillable = ['type', 'name', 'parent_id'];

    protected $hidden = ['type', 'created_at', 'updated_at'];

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'id', 'parent_id');
    }

    public function scopeTop($query)
    {
        return $query->where('parent_id', 0);
    }

    public function tree(string $type) :array
    {
        $top = self::top()->where(compact('type'))->get()->toArray();

        return $this->getChildren($top);
    }

    /**
     * @param array|integer $parent
     * @return array
     */
    public function getChildren($parent): array
    {
        // 查询多个
        if (is_array($parent)) {
            foreach ($parent as $key => $item) {
                $parent[$key]['children'] = [];
                if ($this->where('parent_id', $item['id'])->count()) {
                    $children = $this->where('parent_id', $item['id'])->get()->toArray();
                    $parent[$key]['children'] = $this->getChildren($children);
                }
            }

            return $parent;
        }

        // 查询单个
        return $this->getChildren($this->where('parent_id', $parent['id'])->get()->toArray());
    }
}
