<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ExamStatus;
use App\Http\Requests\ExamRequest;
use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\User;
use App\Transformers\ExamTransformer;
use App\Http\Controllers\Controller;

class ExamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $exams = Exam::own()->filtered()->paginate(self::limit());

        return $this->response->paginator($exams, new ExamTransformer());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ExamRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(ExamRequest $request)
    {
        $exam = new Exam($request->all());
        $exam->user_id = auth()->id();
        $exam->save();
        $exam->classrooms()->attach($request->classroom_ids);

        return $this->response->item($exam, new ExamTransformer())->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     *
     * @param ExamRequest $exam
     * @return \Illuminate\Http\Response
     */
    public function show(Exam $exam)
    {
        return $this->response->item($exam, new ExamTransformer());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ExamRequest $request
     * @param ExamRequest $exam
     * @return \Illuminate\Http\Response
     */
    public function update(ExamRequest $request, Exam $exam)
    {
        if ($exam->status !== ExamStatus::Unstart) {
            $this->response->errorForbidden(__('Only unstart status allows delete or update.'));
        }

        $exam->fill($request->all())->save();

        $exam->classrooms()->sync($request->classroom_ids);

        return $this->response->item($exam, new ExamTransformer());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param ExamRequest $exam
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Exam $exam)
    {
        if ($exam->status !== ExamStatus::Unstart) {
            $this->response->errorForbidden(__('Only unstart status allows delete or update.'));
        }

        $exam->delete();

        return $this->response->noContent();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ExamRequest $request
     * @param ExamRequest $exam
     * @return \Illuminate\Http\Response
     */
    public function end(Exam $exam)
    {
        $course = $exam->course()->first();

        // 将所有未答题学员进行数据添加
        $users = User::whereIn('classroom_id', request('classroom_ids'))->get(['id', 'classroom_id']);
        \DB::transaction(function () use ($users, $exam, $course) {
            $results = ExamResult::whereIn('user_id', $users->pluck('id')->toArray())->where('exam_id', $exam->id)->get();
            foreach ($users as $user) {
                $result = $results->where('user_id', $user->id)->first();
                if ($result && !$result->is_finished) {
                    $result->is_finished = true;
                    $result->save();
                } else if (!$result) {
                    ExamResult::create([
                        'classroom_id' => $user->classroom_id,
                        'course_id' => $course->id,
                        'user_id' => $user->id,
                        'exam_id' => $exam->id,
                        'is_finished' => true,
                        'is_participated' => false
                    ]);
                }
            }
        });

        return $this->response->noContent();
    }
}
