<?php

namespace App\Http\Controllers\MiniApp;

use App\Enums\ExamStatus;
use App\Models\ClassroomExam;
use App\Models\Exam;
use App\Models\ExamResult;
use App\Transformers\QuestionTransformer;
use App\Http\Controllers\Controller;
use App\Transformers\ExamTransformer;

class ExamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function today()
    {
        $me = auth()->user();

        $examIds = ClassroomExam::where('classroom_id', $me->classroom_id)->pluck('exam_id');

        $exams = Exam::today()->whereIn('id', $examIds)->get();

        return $this->response->collection($exams, new ExamTransformer());
    }

    /**
     * Display the specified resource.
     *
     * @param Exam $exam
     * @return \Illuminate\Http\Response
     */
    public function start($exam)
    {
        $exam = Exam::today()->findOrFail($exam);
        if ($exam->status !== ExamStatus::Ongoing) {
            $this->response->errorForbidden(__('Exam is not ongoing.'));
        }
        $me = auth()->user();
        $result = ExamResult::where('user_id', $me->id)->where('exam_id', $exam->id)->first();
        if ($result && $result->is_finished) {
            $this->response->errorForbidden(__('Exam is end.'));
        }

        $classroomExam = ClassroomExam::where('classroom_id', $me->classroom_id)
            ->where('exam_id', $exam->id)
            ->firstOrFail();
        if (!$result) {
            ExamResult::create([
                'user_id' => $me->id,
                'exam_id' => $classroomExam->exam_id,
                'questions_count' => $classroomExam->exam->questions_count,
                'total_score' => $classroomExam->exam->total_score,
                'classroom_id' => $me->classroom_id,
                'course_id' => $exam->course_id,
            ]);
        }

        $questions = $classroomExam->exam->questions()->select(['id', 'title', 'options', 'type', 'accuracy', 'answered_count'])->get();

        $answeringCount = ExamResult::where('exam_id', $exam->id)->count();

        return $this->response->collection($questions, new QuestionTransformer())->setMeta([
            'answering_count' => $answeringCount
        ]);
    }
}
