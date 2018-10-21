<?php

namespace App\Http\Controllers\MiniApp;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Transformers\UserTransformer;

class ClassmateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $me = auth()->user();

        $data = User::where('group_id', $me->group_id)->get();

        return $this->response->collection($data, new UserTransformer());
    }
}
