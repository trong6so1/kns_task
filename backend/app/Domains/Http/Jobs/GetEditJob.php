<?php

/**
 * This file is part of RiverCrane's project.
 * (c) 2022 RiverCrane Corp.
 *
 * @copyright 2022 RiverCrane Corp.
 */

namespace App\Domains\Http\Jobs;

use App\Data\Scopes\WhereScope;
use Lucid\Units\Job;

class GetEditJob extends Job
{
    protected $id;
    protected $select;
    protected $modelClass;
    protected $nestedRelations;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(int $id, array $select, string $modelClass, array $nestedRelations = [])
    {
        $this->id = $id;
        $this->select = $select;
        $this->modelClass = $modelClass;
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
        $model = $model
            ->withoutGlobalScope(WhereScope::class)
            ->select($this->select)->with($this->nestedRelations)
            ->find($this->id);

        return !empty($model) ? $model->toArray() : false;
    }
}
