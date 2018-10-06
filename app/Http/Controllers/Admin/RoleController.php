<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\RoleRequest;
use App\Models\Role;
use App\Transformers\RoleTransformer;
use App\Http\Controllers\Controller;

class RoleController extends Controller
{
    public function index()
    {
        return $this->response->collection(Role::all(), new RoleTransformer());
    }

    public function store(RoleRequest $request)
    {
        return $this->response->item(Role::create($request->all()), new RoleTransformer())
            ->setStatusCode(201);
    }

    public function update(RoleRequest $request, Role $role)
    {
        $role->fill($request->all())->save();

        return $this->response->item($role, new RoleTransformer())
            ->setStatusCode(201);
    }

    public function destroy(Role $permission)
    {
        $permission->delete();

        return $this->response->noContent();
    }
}
