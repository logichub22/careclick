<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\CronJobController;

class SyncRibyRequests extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:ribyrequests';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetches new loan requests and sends email notification to Firstsource admin';

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
    public function handle(CronJobController $cronJobController)
    {
        $cronJobController->syncRibyApplications();
    }
}
