<?php

/**
 * This file is part of RiverCrane's project.
 * (c) 2022 RiverCrane Corp.
 *
 * @copyright 2022 RiverCrane Corp.
 */

namespace App\Domains\Http\Jobs;

use Lucid\Units\Job;

class FindModelJob extends Job
{
    protected $modelClass;
    protected $id;
    protected $nestedRelations;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $modelClass, string|int $id, array $nestedRelations = [])
    {
        $this->modelClass = $modelClass;
        $this->id = $id;
        $this->nestedRelations = $nestedRelations;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $model = new $this->modelClass();
        $model = $model->where('id', $this->id);

        if (!empty($this->nestedRelations)) {
            $model = $model->with($this->nestedRelations);
        }

        return $model->first();
    }
}
