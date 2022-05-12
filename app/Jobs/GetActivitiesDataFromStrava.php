<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\ActivityService;

class GetActivitiesDataFromStrava implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    protected $tokenData;
    protected $onlyOnePage;
    protected ActivityService $service;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($tokenData, ActivityService $service, $onlyOnePage = false)
    {
        $this->service = $service;

        $this->tokenData = $tokenData;

        $this->onlyOnePage = $onlyOnePage;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->service->getActivitiesDataFromStrava($this->tokenData, $this->onlyOnePage);
    }
}
