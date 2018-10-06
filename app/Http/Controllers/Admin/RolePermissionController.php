<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\RolePermissionRequest;
use App\Models\Permission;
use App\Models\Role;
use App\Transformers\PermissionTransformer;
use App\Http\Controllers\Controller;

class RolePermissionController extends Controller
{
    public function index(Role $role)
    {
        return $this->response->collection($role->permissions, new PermissionTransformer());
    }

    public function store(RolePermissionRequest $request, Role $role)
    {
        $role->givePermissionTo($request->permission_id);

        return $this->response->created();
    }

    public function destroy(Role $role, Permission $permission)
    {
        $role->revokePermissionTo($permission);

        return $this->response->noContent();
    }
}
