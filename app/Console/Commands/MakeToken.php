<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MakeToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:token';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '生成一个一年有效期的 JWT Token';

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
        $user = $this->ask('输入用户的ID');

        $token = auth()->setTTL(60 * 60 * 24 * 365)->tokenById($user);

        $this->info($token);
    }
}
