<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Http\Controllers\Traits\StravaActivitiesTrait;

class GetActivitiesDataFromStrava implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;
    use StravaActivitiesTrait;

    protected $tokenData;
    protected $onlyOnePage;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($tokenData, $onlyOnePage = false)
    {
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
        $this->getActivitiesDataFromStrava($this->tokenData, $this->onlyOnePage);
    }
}
