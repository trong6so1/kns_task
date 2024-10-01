<?php

/**
 * This file is part of RiverCrane's project.
 * (c) 2022 RiverCrane Corp.
 *
 * @copyright 2022 RiverCrane Corp.
 */

namespace App\Domains\Http\Jobs;

use Illuminate\Database\Eloquent\Builder;
use Lucid\Units\Job;

class GetRelateDataJob extends Job
{
    protected $query;
    protected $nestedRelations;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($query, array $nestedRelations = [])
    {
        $this->query = $query;
        $this->nestedRelations = $nestedRelations;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        return $this->query->with($this->nestedRelations);
    }
}
