<?php

namespace App\Console\Commands;

use App\Jobs\SendEmail;
use Illuminate\Console\Command;

class SendMailTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:mail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send testing mail';

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
     * @return int
     */
    public function handle()
    {
        SendEmail::dispatchNow(env('ADMIN_MAIL'), ['title' => 'test send mail', 'main' => 'test content']);

        return 0;
    }
}
