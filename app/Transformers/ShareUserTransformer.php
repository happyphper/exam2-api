<?php
/**
 * Created by PhpStorm.
 * User: wangbaolong
 * Date: 2018/8/19
 * Time: 14:52
 */

namespace App\Transformers;

use App\Models\Share;
use League\Fractal\TransformerAbstract;

class ShareUserTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['user', 'share_user'];

    public function transform(Share $model)
    {
        return [
            'id' => $model->id,
            'user_id' => $model->user_id,
            'share_user_id' => $model->share_user_id,
            'type' => $model->type,
        ];
    }

    public function includeShareUser(Share $model)
    {
        return $this->item($model->share_user, new UserTransformer());
    }

    public function includeUser(Share $model)
    {
        return $this->item($model->user, new UserTransformer());
    }
}