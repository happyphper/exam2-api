<?php

namespace App\Http\Controllers\MiniApp;

use App\Http\Controllers\Controller;
use Hash;

class PasswordController extends Controller
{
    /**
     * @return \Dingo\Api\Http\Response
     */
    public function update()
    {
        $this->validate(request(), [
            'old_password' => 'required',
            'password' => 'required|min:6|confirmed'
        ]);

        $user = auth()->user();
        if (!Hash::check(request('old_password'), $user->getAuthPassword())) {
            $this->response->errorBadRequest('密码错误。');
        }

        $user->password = bcrypt(request('password'));
        $user->save();

        return $this->response->noContent();
    }
}
