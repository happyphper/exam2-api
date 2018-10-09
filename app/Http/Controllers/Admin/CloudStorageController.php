<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Storage;

class CloudStorageController extends Controller
{
    /**
     * 获取 Token
     *
     * @return mixed
     */
    public function token()
    {
        $disk = Storage::disk('qiniu');

        $token = $disk->getDriver()->uploadToken();

        return $this->response->array([
            'domain' => config('filesystems.disks.qiniu.domains.default'),
            'name'  => 'images/' . now()->toDateString() . '-' . str_random(4),
            'token' => $token
        ]);
    }

    /**
     * 删除
     *
     * @param $name
     * @return \Dingo\Api\Http\Response
     */
    public function destroy($name)
    {
        $disk = Storage::disk('qiniu');

        $disk->delete($name);

        return $this->response->noContent();
    }
}
