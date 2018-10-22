<?php

namespace App\Console\Commands;

use App\Enums\TestStatus;
use App\Models\Test;
use Illuminate\Console\Command;

class StartTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'start:test';

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
        $count = Test::where('started_at', '<', now())->where('questions_count', '>', 0)->where('status', TestStatus::Unstart)->update(['status' => TestStatus::Ongoing]);

        $this->info("{$count} 个考试的状态的已修改为开考状态。");
    }
}
