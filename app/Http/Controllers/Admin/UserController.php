<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\BulkUserRequest;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Transformers\UserTransformer;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::filtered()->paginate(self::limit());

        return $this->response->paginator($users, new UserTransformer());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param UserRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        $user = new User($request->all());
        // 生成初始密码
        $user->password = bcrypt($request->password ?? 123456);
        $user->save();

        return $this->response->item($user, new UserTransformer())->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     *
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return $this->response->item($user, new UserTransformer());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UserRequest $request
     * @param User        $user
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, User $user)
    {
        $user->fill($request->all())->save();

        return $this->response->item($user, new UserTransformer());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(User $user)
    {
        $user->delete();

        return $this->response->noContent();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Dingo\Api\Http\Response
     */
    public function bulk(BulkUserRequest $request)
    {
        $users = $request->users;

        foreach ($users as $userData) {
            $user           = new User($userData);
            $user->group_id = $request->group_id;
            $user->password = $request->password ? bcrypt($request->password) : bcrypt(123456);
            $user->save();
        }

        return $this->response->noContent();
    }
}
