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

class PaginateJob extends Job
{
    protected $query;
    protected $input;
    protected $defaultLimit;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($query, array $input, int $defaultLimit)
    {
        $this->query = $query;
        $this->input = $input;
        $this->defaultLimit = $defaultLimit;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        return $this->query->paginate($this->input['per_page'] ?? $this->defaultLimit)->toArray();
    }
}
