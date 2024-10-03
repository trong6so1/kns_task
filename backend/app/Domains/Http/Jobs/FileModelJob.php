<?php

namespace App\Domains\Http\Jobs;

use Lucid\Units\Job;

class FileModelJob extends Job
{
    /**
     * Create a new job instance.
     *
     * @return void
     */
    private $model;
    private $id;
    private $nestedRelations;

    public function __construct($model, $id, array $nestedRelations)
    {
        $this->model = $model;
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
        $model = new $this->model;
        $model = $model->where('id', $this->id)->with($this->nestedRelations)->first();
        return $model;
    }
}
