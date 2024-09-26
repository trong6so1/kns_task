<?php

/**
 * This file is part of RiverCrane's project.
 * (c) 2022 RiverCrane Corp.
 *
 * @copyright 2022 RiverCrane Corp.
 */

namespace App\Domains\Http\Jobs;

use Lucid\Units\Job;

class NewClassJob extends Job
{
    protected $modelClass;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $modelClass)
    {
        $this->modelClass = $modelClass;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        return new $this->modelClass();
    }
}
