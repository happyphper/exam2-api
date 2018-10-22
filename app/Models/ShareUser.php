<?php

namespace App\Models;

use App\Traits\OwnTrait;
use Illuminate\Database\Eloquent\Model;

class ShareUser extends Model
{
    use OwnTrait;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function share_user()
    {
        return $this->belongsTo(User::class, 'share_user_id', 'id');
    }
}
