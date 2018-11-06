<?php

namespace App\Console\Commands;

use App\Enums\TestStatus;
use App\Models\ClassroomTest;
use App\Models\Test;
use App\Models\TestResult;
use App\Models\User;
use Illuminate\Console\Command;

class EndTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'end:test';

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
        $tests = Test::where('status', TestStatus::Ongoing)
            ->where('ended_at', '<', now())
            ->where('questions_count', '>', 0)
            ->get();

        \DB::transaction(function () use ($tests) {
            $tests->each(function ($test) {
                $classroomIds = ClassroomTest::where('test_id', $test->id)->pluck('classroom_id');
                // 将所有未答题学员进行数据添加
                $users = User::whereIn('classroom_id', $classroomIds->toArray())->get(['id', 'classroom_id']);
                $results = TestResult::whereIn('user_id', $users->pluck('id')->toArray())->where('test_id', $test->id)->get();
                foreach ($users as $user) {
                    $result = $results->where('user_id', $user->id)->first();
                    if ($result && !$result->is_finished) {
                        $result->is_finished = true;
                        $result->save();
                    } else {
                        TestResult::create([
                            'classroom_id' => $user->classroom_id,
                            'course_id' => $test->course_id,
                            'user_id' => $user->id,
                            'test_id' => $test->id,
                            'is_finished' => true,
                            'is_participated' => false
                        ]);
                    }
                }
                $test->status = TestStatus::End;
                $test->save();
            });
        });
    }
}
