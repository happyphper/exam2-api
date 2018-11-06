<?php

namespace App\Console\Commands;

use App\Enums\ExamStatus;
use App\Models\ClassroomExam;
use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\User;
use Illuminate\Console\Command;

class EndExam extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'end:exam';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '结束考试，并将未答题学员补充到测评上。';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // 获取所有进行并结束了的考试
        $exams = Exam::where('status', ExamStatus::Ongoing)
            ->where('ended_at', '<', now())
            ->where('questions_count', '>', 0)
            ->get();

        \DB::transaction(function () use ($exams) {
            $exams->each(function ($exam) {
                $classroomIds = ClassroomExam::where('exam_id', $exam->id)->pluck('classroom_id');
                // 将所有未答题学员进行数据添加
                $users = User::whereIn('classroom_id', $classroomIds->toArray())->get(['id', 'classroom_id']);
                $results = ExamResult::whereIn('user_id', $users->pluck('id')->toArray())->where('exam_id', $exam->id)->get();
                foreach ($users as $user) {
                    $result = $results->where('user_id', $user->id)->first();
                    if ($result && !$result->is_finished) {
                        $result->is_finished = true;
                        $result->save();
                    } else {
                        ExamResult::create([
                            'classroom_id' => $user->classroom_id,
                            'course_id' => $exam->course_id,
                            'user_id' => $user->id,
                            'exam_id' => $exam->id,
                            'is_finished' => true,
                            'is_participated' => false
                        ]);
                    }
                }
                $exam->status = ExamStatus::End;
                $exam->save();
            });
        });
    }
}
