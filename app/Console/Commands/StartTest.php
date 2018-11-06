<?php

namespace App\Console\Commands;

use App\Enums\ExamStatus;
use App\Models\Exam;
use Illuminate\Console\Command;

class StartExam extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'start:exam';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '将开考时间已到的考试的状态设置为开考状态';

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
        $count = Exam::where('started_at', '<', now())->where('questions_count', '>', 0)->where('status', ExamStatus::Unstart)->update(['status' => ExamStatus::Ongoing]);

        $this->info("{$count} 个考试的状态的已修改为开考状态。");
    }
}
