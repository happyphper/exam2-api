<?php

namespace App\Http\Controllers\MiniApp;

use App\Http\Controllers\Controller;

class PhoneController extends Controller
{
    /**
     * @return \Dingo\Api\Http\Response
     */
    public function update()
    {
        $this->validate(request(), [
            'phone' => 'required|regex:/1[3-9]\d{9}/|unique:users,phone'
        ]);

        $user = auth()->user();
        $user->phone = request('phone');
        $user->save();

        return $this->response->noContent();
    }
}
