<?php

/**
 * This file is part of RiverCrane's project.
 * (c) 2022 RiverCrane Corp.
 *
 * @copyright 2022 RiverCrane Corp.
 */

namespace App\Domains\Http\Jobs;

use App\Data\Models\ProcessReleaseJobQueue;
use Lucid\Units\Job;

class SaveReleaseQueueJob extends Job
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
        $processReleaseJobQueue = isset($this->params['id']) ?
            ProcessReleaseJobQueue::find($this->params['id']) :
            new ProcessReleaseJobQueue();

        if (isset($this->params['management_tool_history_id'])) {
            $processReleaseJobQueue->management_tool_history_id = $this->params['management_tool_history_id'];
        }

        if (isset($this->params['success_count'])) {
            $processReleaseJobQueue->success_count = $this->params['success_count'];
        }

        if (isset($this->params['not_change_count'])) {
            $processReleaseJobQueue->not_change_count = $this->params['not_change_count'];
        }

        if (isset($this->params['failed_count'])) {
            $processReleaseJobQueue->failed_count = $this->params['failed_count'];
        }

        if (isset($this->params['sum_count'])) {
            $processReleaseJobQueue->sum_count = $this->params['sum_count'];
        }

        if (isset($this->params['created_by'])) {
            $processReleaseJobQueue->created_by = $this->params['created_by'];
        }

        if (isset($this->params['updated_by'])) {
            $processReleaseJobQueue->updated_by = $this->params['updated_by'];
        }

        $processReleaseJobQueue->save();

        return $processReleaseJobQueue;
    }
}
