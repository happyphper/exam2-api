<?php

namespace App\Http\Controllers\Admin;

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
        $records = Record::paginate(self::limit());

        return $this->response->paginator($records, new RecordTransformer());
    }

    /**
     * Display the specified resource.
     *
     * @param Record $record
     * @return \Illuminate\Http\Response
     */
    public function show(Record $record)
    {
        return $this->response->item($record, new RecordTransformer());
    }
}
