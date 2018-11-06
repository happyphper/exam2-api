<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\QuestionRequest;
use App\Http\Requests\ShareRequest;
use App\Models\Question;
use App\Models\Share;
use App\Models\User;
use App\Transformers\ShareUserTransformer;
use App\Http\Controllers\Controller;

class ShareController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Share::own()->filtered()->get();

        return $this->response->collection($data, new ShareUserTransformer());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param QuestionRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(ShareRequest $request)
    {
        $user = User::where('phone', $request->phone)->firstOrFail();

        $item = \DB::transaction(function () use ($request, $user) {
            $item = new Share();
            $item->user_id = auth()->id();
            $item->share_user_id = $user->id;
            $item->type = $request->type;
            $item->save();
            $anotherItem = new Share();
            $anotherItem->share_user_id = auth()->id();
            $anotherItem->user_id = $user->id;
            $anotherItem->type =  $request->type;
            $anotherItem->save();
            return $item;
        });

        return $this->response->item($item, new ShareUserTransformer())->setStatusCode(201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Question $question
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy($share)
    {
        $item = Share::findOrFail($share);

        \DB::transaction(function () use($item) {
            $anotherItem = Share::where('share_user_id', $item->user_id)
                ->where('user_id', $item->share_user_id)
                ->where('type', $item->type)
                ->first();
            $anotherItem->delete();
            $item->delete();
        });

        return $this->response->noContent();
    }
}
