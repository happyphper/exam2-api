<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\PermissionRequest;
use App\Models\Permission;
use App\Transformers\PermissionTransformer;
use App\Http\Controllers\Controller;

class PermissionController extends Controller
{
    public function index()
    {
        return $this->response->collection(Permission::all(), new PermissionTransformer());
    }

    public function store(PermissionRequest $request)
    {
        return $this->response->item(Permission::create($request->all()), new PermissionTransformer())
            ->setStatusCode(201);
    }

    public function update(PermissionRequest $request, Permission $permission)
    {
        $permission->fill($request->all())->save();

        return $this->response->item($permission, new PermissionTransformer())
            ->setStatusCode(201);
    }

    public function destroy(Permission $permission)
    {
        $permission->delete();

        return $this->response->noContent();
    }
}
