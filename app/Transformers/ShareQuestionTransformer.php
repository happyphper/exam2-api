<?php
/**
 * Created by PhpStorm.
 * User: wangbaolong
 * Date: 2018/8/19
 * Time: 14:52
 */

namespace App\Transformers;

use App\Models\ShareUser;
use League\Fractal\TransformerAbstract;

class ShareQuestionTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['user', 'share_user'];

    public function transform(ShareUser $model)
    {
        return [
            'user_id' => $model->user_id,
            'share_user_id' => $model->share_user_id,
        ];
    }

    public function includeShareUser(ShareUser $model)
    {
        return $this->item($model->share_user, new UserTransformer());
    }

    public function includeUser(ShareUser $model)
    {
        return $this->item($model->user, new UserTransformer());
    }
}