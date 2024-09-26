<?php

/**
 * This file is part of RiverCrane's project.
 * (c) 2022 RiverCrane Corp.
 *
 * @copyright 2022 RiverCrane Corp.
 */

namespace App\Domains\Http\Jobs;

use Lucid\Units\Job;
use App\Data\Scopes\CountryRelationshipScope;
use App\Data\Scopes\CountryScope;
use App\Data\Scopes\OrderRelationshipScope;

class WithoutGlobalScopesJob extends Job
{
    protected $model;

    /**
     * Create a new job instance.
     *
     * @return void
     */
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
        return $this->model::withoutGlobalScopes([
            CountryRelationshipScope::class,
            OrderRelationshipScope::class,
            CountryScope::class,
        ]);
    }
}
