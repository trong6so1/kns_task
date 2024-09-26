<?php

/**
 * This file is part of RiverCrane's project.
 * (c) 2022 RiverCrane Corp.
 *
 * @copyright 2022 RiverCrane Corp.
 */

namespace App\Domains\Http\Jobs;

use App\Data\Models\ProcessImportJobQueue;
use Lucid\Units\Job;

class SaveImportQueueJob extends Job
{
    protected $params;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $params)
    {
        $this->params = $params;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $processImportJobQueue = isset($this->params['id']) ?
            ProcessImportJobQueue::find($this->params['id']) :
            new ProcessImportJobQueue();

        if (isset($this->params['management_tool_history_id'])) {
            $processImportJobQueue->management_tool_history_id = $this->params['management_tool_history_id'];
        }

        if (isset($this->params['success_count'])) {
            $processImportJobQueue->success_count = $this->params['success_count'];
        }

        if (isset($this->params['updated_count'])) {
            $processImportJobQueue->updated_count = $this->params['updated_count'];
        }

        if (isset($this->params['not_change_count'])) {
            $processImportJobQueue->not_change_count = $this->params['not_change_count'];
        }

        if (isset($this->params['failed_count'])) {
            $processImportJobQueue->failed_count = $this->params['failed_count'];
        }

        if (isset($this->params['sum_count'])) {
            $processImportJobQueue->sum_count = $this->params['sum_count'];
        }

        if (isset($this->params['created_by'])) {
            $processImportJobQueue->created_by = $this->params['created_by'];
        }

        if (isset($this->params['updated_by'])) {
            $processImportJobQueue->updated_by = $this->params['updated_by'];
        }

        $processImportJobQueue->save();

        return $processImportJobQueue;
    }
}
