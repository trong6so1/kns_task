<?php

/**
 * This file is part of RiverCrane's project.
 * (c) 2022 RiverCrane Corp.
 *
 * @copyright 2022 RiverCrane Corp.
 */

namespace App\Domains\Http\Jobs;

use App\Data\Models\ManagementToolHistory;
use Lucid\Units\Job;

class GetHistoryReleaseJob extends Job
{
    protected $module;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $module)
    {
        $this->module = $module;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $nestedRelations = ['createdBy:id,name'];
        $nestedRelations['processReleaseJobQueue.releaseHistory'] = function ($query) {
            $query->where('status', 9)
                ->select('id', 'process_release_job_queue_id', 'identification_field', 'status', 'message');
        };

        return ManagementToolHistory::where('module', $this->module)->with($nestedRelations);
    }
}
