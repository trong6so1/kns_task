<?php

/**
 * This file is part of RiverCrane's project.
 * (c) 2022 RiverCrane Corp.
 *
 * @copyright 2022 RiverCrane Corp.
 */

namespace App\Domains\Http\Jobs;

use App\Data\Scopes\CountryRelationshipScope;
use App\Data\Scopes\OrderRelationshipScope;
use App\Data\Scopes\CountryScope;
use Lucid\Units\Job;

class BuildListQueryWithoutScopeJob extends Job
{
    protected $modelClass;
    protected $select;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $modelClass, $select)
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

        $query = $this->modelClass::withoutGlobalScopes([
            CountryRelationshipScope::class,
            OrderRelationshipScope::class,
            CountryScope::class,
        ]);

        return $query->select($this->select);
    }
}
