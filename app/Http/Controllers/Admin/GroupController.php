<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\GroupRequest;
use App\Models\Group;
use App\Http\Controllers\Controller;
use App\Transformers\GroupTransformer;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $groups = Group::filtered()->paginate(self::limit());

        return $this->response->paginator($groups, new GroupTransformer());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param GroupRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(GroupRequest $request, Group $group)
    {
        $group->fill($request->all());
        $group->save();

        return $this->response->item($group, new GroupTransformer())->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     *
     * @param Group $group
     * @return \Illuminate\Http\Response
     */
    public function show(Group $group)
    {
        return $this->response->item($group, new GroupTransformer());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param GroupRequest $request
     * @param Group $group
     * @return \Illuminate\Http\Response
     */
    public function update(GroupRequest $request, Group $group)
    {
        $group->fill($request->all())->save();

        return $this->response->item($group, new GroupTransformer());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Group $group
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Group $group)
    {
        $group->delete();

        return $this->response->noContent();
    }
}
