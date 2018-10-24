<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PasswordRequest;

class PasswordController extends Controller
{
    /**
     * 重置密码
     *
     * @param PasswordRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function reset(PasswordRequest $request)
    {
        $me = auth()->user();

        $originalPassword = $me->getAuthPassword();

        if (!\Hash::check($request->original_password, $originalPassword)) {
            $this->response->errorBadRequest('密码错误');
        }

        $me->password = bcrypt($request->password);
        $me->save();

        auth()->logout();

        return $this->response->noContent();
    }
}
