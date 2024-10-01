<?php

namespace App\Domains\Auth\Jobs;

use Lucid\Units\Job;

class CheckPermissionJob extends Job
{
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        return true;
    }
}
