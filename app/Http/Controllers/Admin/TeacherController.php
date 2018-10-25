<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\TeacherRequest;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Transformers\UserTransformer;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::role('teacher')->filtered()->paginate(self::limit());

        return $this->response->paginator($users, new UserTransformer());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param TeacherRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function store(TeacherRequest $request)
    {
        $user = new User($request->all());
        // 生成初始密码
        $user->password =bcrypt($request->password ?? 123456);
        $user->save();

        $user->assignRole('teacher');

        return $this->response->item($user, new UserTransformer())->setStatusCode(201);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param TeacherRequest $request
     * @param User           $user
     * @return \Illuminate\Http\Response
     */
    public function update(TeacherRequest $request, User $teacher)
    {
        $teacher->fill($request->all())->save();

        return $this->response->item($teacher, new UserTransformer());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(User $teacher)
    {
        $teacher->delete();

        return $this->response->noContent();
    }
}
