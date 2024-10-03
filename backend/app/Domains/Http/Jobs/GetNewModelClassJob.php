<?php

namespace App\Domains\Http\Jobs;

use Lucid\Units\Job;

class GetNewModelClassJob extends Job
{
    /**
     * Create a new job instance.
     *
     * @return void
     */
    private $model;
    public function __construct($model)
    {
        $this->model = $model;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        return new $this->model;
    }
}
