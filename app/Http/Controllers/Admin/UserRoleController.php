<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\UserRoleRequest;
use App\Models\Role;
use App\Models\User;
use App\Transformers\RoleTransformer;
use App\Http\Controllers\Controller;

class UserRoleController extends Controller
{
    public function index(User $user)
    {
        return $this->response->collection($user->roles, new RoleTransformer());
    }

    public function store(UserRoleRequest $request, User $user)
    {
        $user->assignRole($request->role_id);

        return $this->response->created();
    }

    public function destroy(User $user, Role $role)
    {
        $user->removeRole($role);

        return $this->response->noContent();
    }
}
