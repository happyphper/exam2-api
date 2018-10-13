<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\AdminUserRequest;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Transformers\UserTransformer;

class AdminUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::role('admin')->filtered()->paginate(self::limit());

        return $this->response->paginator($users, new UserTransformer());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param AdminUserRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function store(AdminUserRequest $request)
    {
        $user = new User($request->all());
        // 生成初始密码
        $user->password =bcrypt($request->password ?? 123456);
        $user->save();

        $user->assignRole('admin');

        return $this->response->item($user, new UserTransformer())->setStatusCode(201);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param AdminUserRequest $request
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function update(AdminUserRequest $request, User $admin_user)
    {
        $admin_user->fill($request->all())->save();

        return $this->response->item($admin_user, new UserTransformer());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(User $admin_user)
    {
        $admin_user->delete();

        return $this->response->noContent();
    }
}
