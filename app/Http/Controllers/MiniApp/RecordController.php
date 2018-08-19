<?php

namespace App\Http\Controllers\MiniApp;

use App\Http\Requests\RecordRequest;
use App\Models\Record;
use App\Transformers\RecordTransformer;
use App\Http\Controllers\Controller;

class RecordController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $me = auth()->user();

        $records = $me->records()->paginate(self::limit());

        return $this->response->paginator($records, new RecordTransformer());
    }

    /**
     * Display the specified resource.
     *
     * @param Record $record
     * @return \Illuminate\Http\Response
     */
    public function show($record)
    {
        $me = auth()->user();

        $record = $me->records()->findOrFail($record);

        return $this->response->item($record, new RecordTransformer());
    }

    /**
     * 提交答卷
     *
     * @param RecordRequest $request
     * @param Record $record
     * @return \Dingo\Api\Http\Response
     */
    public function store(RecordRequest $request, Record $record)
    {
        $me = auth()->user();

        $record->user_id = $me->id;
        $record->fill($request->all());
        $record->save();

        // TODO 计算得分

        return $this->response->item($record, new RecordTransformer())->setStatusCode(201);
    }
}
