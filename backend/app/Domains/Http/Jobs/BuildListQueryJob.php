<?php

/**
 * This file is part of RiverCrane's project.
 * (c) 2022 RiverCrane Corp.
 *
 * @copyright 2022 RiverCrane Corp.
 */

namespace App\Domains\Http\Jobs;

use Lucid\Units\Job;

class BuildListQueryJob extends Job
{
    protected $modelClass;
    protected $select;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $select = [], string $modelClass)
    {
        $this->modelClass = $modelClass;
        $this->select = $select;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $query = new $this->modelClass();
        if(!empty($this->select))
        {
            $query = $query->select($this->select);
        }
        return $query;
    }
}
