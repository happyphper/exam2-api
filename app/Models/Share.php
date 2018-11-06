<?php

namespace App\Models;

use App\Traits\OwnTrait;
use Illuminate\Database\Eloquent\Model;
use Jedrzej\Searchable\SearchableTrait;

class Share extends Model
{
    use SearchableTrait;
    use OwnTrait;

    public $searchable = ['share_user:name', 'type'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function share_user()
    {
        return $this->belongsTo(User::class, 'share_user_id', 'id');
    }
}
