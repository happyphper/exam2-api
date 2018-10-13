<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\QuestionRequest;
use App\Models\Question;
use App\Models\ShareQuestion;
use App\Models\User;
use App\Transformers\ShareQuestionTransformer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ShareQuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = ShareQuestion::own()->get();

        return $this->response->collection($data, new ShareQuestionTransformer());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param QuestionRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $shareUser = User::where('phone', $request->phone)->firstOrFail();

        $item = new ShareQuestion();
        $item->user_id = auth()->id();
        $item->share_user_id = $shareUser->id;
        $item->save();
        $antherItem = new ShareQuestion();
        $antherItem->share_user_id = auth()->id();
        $antherItem->user_id = $shareUser->id;
        $antherItem->save();

        return $this->response->item($item, new ShareQuestionTransformer())->setStatusCode(201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Question $question
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy($shareQuestion)
    {
        $item = ShareQuestion::where('user_id', auth()->id())->where('share_user_id', $shareQuestion)->first();
        $item->delete();

        $anotherItem = ShareQuestion::where('share_user_id', auth()->id())->where('user_id', $shareQuestion)->first();
        $anotherItem->delete();
        return $this->response->noContent();
    }
}
