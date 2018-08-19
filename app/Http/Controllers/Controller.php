<?php

namespace App\Http\Controllers;

use Dingo\Api\Routing\Helpers;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    use Helpers;

    /**
     * 限制单页显示最大数量
     *
     * @param null $perPage
     * @return array|\Illuminate\Http\Request|int|string
     */
    public static function limit($perPage = null)
    {
        $perPage = $perPage ? $perPage : request('per_page', 25);

        return $perPage > 100 ? 100 : $perPage;
    }
}
